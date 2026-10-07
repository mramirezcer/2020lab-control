<?php

namespace Database\Seeders;

use App\Models\Fase;
use App\Models\Proceso;
use Illuminate\Database\Seeder;
use RuntimeException;

class ProcesosProyectoSeeder extends Seeder
{
    /**
     * Carga idempotente del catalogo maestro de procesos de 2020Lab V2.
     *
     * Mantiene metadatos maestros: clave, nombre, descripcion y orden.
     * Si el proceso ya existe, NO reinicia estados, fechas, responsables,
     * verificadores, prioridad ni porcentaje de avance.
     */
    public function run(): void
    {
        $ruta = database_path('data/plan_maestro_2020lab_v2.json');

        if (! is_file($ruta)) {
            throw new RuntimeException('No existe el catalogo maestro de procesos.');
        }

        $catalogo = json_decode(
            file_get_contents($ruta),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        foreach ($catalogo['phases'] as $faseCatalogo) {
            $fase = Fase::query()
                ->where('clave', $faseCatalogo['key'])
                ->first();

            if (! $fase) {
                throw new RuntimeException(
                    'No existe la fase requerida: '.$faseCatalogo['key']
                );
            }

            foreach ($faseCatalogo['processes'] as $procesoCatalogo) {
                $proceso = Proceso::query()->firstOrNew([
                    'id_fase' => $fase->id_fase,
                    'clave' => $procesoCatalogo['key'],
                ]);

                $esNuevo = ! $proceso->exists;

                $proceso->nombre = $procesoCatalogo['name'];
                $proceso->descripcion = sprintf(
                    'Proceso funcional consolidado. Actividades fuente: %s.',
                    implode(', ', $procesoCatalogo['activity_ids'])
                );
                $proceso->orden = $procesoCatalogo['order'];

                if ($esNuevo) {
                    $proceso->estado_desarrollo = 'POR_REALIZAR';
                    $proceso->estado_verificacion = 'NO_VERIFICADO';
                    $proceso->prioridad = 'MEDIA';
                    $proceso->porcentaje_avance = 0;
                }

                $proceso->save();
            }
        }
    }
}