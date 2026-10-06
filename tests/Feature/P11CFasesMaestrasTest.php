<?php

namespace Tests\Feature;

use App\Models\Fase;
use Database\Seeders\FasesProyectoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class P11CFasesMaestrasTest extends TestCase
{
    use RefreshDatabase;

    public function test_carga_exactamente_las_16_fases_en_el_orden_acordado(): void
    {
        $this->seed(FasesProyectoSeeder::class);

        $this->assertDatabaseCount('fases', 16);

        $esperadas = [
            'F0' => 'Fundaciones, Arquitectura y Modelo de Datos',
            'F1' => 'Alta de laboratorio',
            'F2' => 'Configuracion inicial y Mantenimiento',
            'F3' => 'Paciente y recepcion',
            'F4' => 'Solicitud y operacion comercial',
            'F5' => 'Preanalitica',
            'F6' => 'Analitica y validacion',
            'F7' => 'Resultados y cierre clinico',
            'F8' => 'Postanalitica, Reportes y Explotacion de Informacion',
            'F9' => 'Informacion gerencial, Estadisticas y KPI',
            'F10' => 'Portales y comunicaciones',
            'F11' => 'Relacion comercial APS',
            'F12' => 'Facturacion del laboratorio - Modulo adicional',
            'F13' => 'Interoperabilidad',
            'F14' => 'Migracion y ciclo de vida',
            'F15' => 'Calidad e Inventario - Modulo adicional',
        ];

        foreach ($esperadas as $indice => $nombre) {
            $orden = (int) substr($indice, 1);

            $this->assertDatabaseHas('fases', [
                'clave' => $indice,
                'nombre' => $nombre,
                'orden' => $orden,
            ]);
        }

        $claves = Fase::query()
            ->orderBy('orden')
            ->pluck('clave')
            ->all();

        $this->assertSame(array_keys($esperadas), $claves);
    }

    public function test_la_carga_es_idempotente(): void
    {
        $this->seed(FasesProyectoSeeder::class);
        $this->seed(FasesProyectoSeeder::class);

        $this->assertDatabaseCount('fases', 16);

        foreach (range(0, 15) as $orden) {
            $this->assertSame(
                1,
                Fase::query()
                    ->where('clave', 'F'.$orden)
                    ->where('orden', $orden)
                    ->count()
            );
        }
    }

    public function test_reseed_no_reinicia_estado_fechas_asignacion_ni_avance(): void
    {
        $this->seed(FasesProyectoSeeder::class);

        $fase = Fase::query()->where('clave', 'F6')->firstOrFail();

        $fase->update([
            'estado_desarrollo' => 'EN_DESARROLLO',
            'estado_verificacion' => 'PENDIENTE',
            'prioridad' => 'CRITICA',
            'fecha_inicio_planificada' => '2026-10-10',
            'fecha_entrega_comprometida' => '2026-11-20',
            'porcentaje_avance' => 37.50,
        ]);

        $this->seed(FasesProyectoSeeder::class);

        $fase->refresh();

        $this->assertSame('EN_DESARROLLO', $fase->estado_desarrollo);
        $this->assertSame('PENDIENTE', $fase->estado_verificacion);
        $this->assertSame('CRITICA', $fase->prioridad);
        $this->assertSame('2026-10-10', $fase->fecha_inicio_planificada->format('Y-m-d'));
        $this->assertSame('2026-11-20', $fase->fecha_entrega_comprometida->format('Y-m-d'));
        $this->assertSame('37.50', $fase->porcentaje_avance);
    }

    public function test_facturacion_y_calidad_inventario_permanecen_identificadas_como_adicionales(): void
    {
        $this->seed(FasesProyectoSeeder::class);

        $f12 = Fase::query()->where('clave', 'F12')->firstOrFail();
        $f15 = Fase::query()->where('clave', 'F15')->firstOrFail();

        $this->assertStringContainsString('Modulo adicional', $f12->nombre);
        $this->assertStringContainsString('Modulo adicional', $f15->nombre);
        $this->assertSame(12, $f12->orden);
        $this->assertSame(15, $f15->orden);
    }
}