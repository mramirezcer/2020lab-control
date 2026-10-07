<?php

namespace Database\Seeders;

use App\Models\Actividad;
use App\Models\Proceso;
use Illuminate\Database\Seeder;
use RuntimeException;

class ActividadesProyectoSeeder extends Seeder
{
    public function run(): void
    {
        $rutaActividades = database_path('data/actividades_plan_maestro_2020lab_v2.json');
        $rutaProcesos = database_path('data/plan_maestro_2020lab_v2.json');

        if (! is_file($rutaActividades) || ! is_file($rutaProcesos)) {
            throw new RuntimeException('Falta catalogo maestro para sembrar actividades.');
        }

        $fuente = json_decode(
            file_get_contents($rutaActividades),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $catalogoProcesos = json_decode(
            file_get_contents($rutaProcesos),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $porClave = [];

        foreach ($fuente['actividades'] as $actividad) {
            $porClave[$actividad['clave']] = $actividad;
        }

        foreach ($catalogoProcesos['phases'] as $faseCatalogo) {
            foreach ($faseCatalogo['processes'] as $procesoCatalogo) {
                $proceso = Proceso::query()
                    ->where('clave', $procesoCatalogo['key'])
                    ->first();

                if (! $proceso) {
                    throw new RuntimeException(
                        'No existe proceso requerido: '.$procesoCatalogo['key']
                    );
                }

                foreach ($procesoCatalogo['activity_ids'] as $indice => $claveActividad) {
                    if (! isset($porClave[$claveActividad])) {
                        throw new RuntimeException(
                            'No existe actividad fuente: '.$claveActividad
                        );
                    }

                    $datos = $porClave[$claveActividad];

                    $actividad = Actividad::query()->firstOrNew([
                        'id_proceso' => $proceso->id_proceso,
                        'clave' => $claveActividad,
                    ]);

                    $esNueva = ! $actividad->exists;

                    $actividad->nombre = $datos['nombre'];
                    $actividad->descripcion = null;
                    $actividad->orden = $indice + 1;
                    $actividad->responsable_recomendado = $datos['responsable_recomendado'];
                    $actividad->revision_apoyo = $datos['revision_apoyo'];

                    if ($esNueva) {
                        $actividad->estado_desarrollo = 'POR_REALIZAR';
                        $actividad->estado_verificacion = 'NO_VERIFICADO';
                        $actividad->prioridad = 'MEDIA';
                        $actividad->peso = 2;
                        $actividad->porcentaje_avance = 0;
                    }

                    $actividad->save();
                }
            }
        }
    }
}