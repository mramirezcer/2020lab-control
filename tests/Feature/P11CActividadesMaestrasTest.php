<?php

namespace Tests\Feature;

use App\Models\Actividad;
use App\Models\Proceso;
use Database\Seeders\ActividadesProyectoSeeder;
use Database\Seeders\FasesProyectoSeeder;
use Database\Seeders\ProcesosProyectoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class P11CActividadesMaestrasTest extends TestCase
{
    use RefreshDatabase;

    private function sembrarBase(): void
    {
        $this->seed(FasesProyectoSeeder::class);
        $this->seed(ProcesosProyectoSeeder::class);
        $this->seed(ActividadesProyectoSeeder::class);
    }

    public function test_carga_exactamente_200_actividades_en_81_procesos(): void
    {
        $this->sembrarBase();

        $this->assertDatabaseCount('fases', 16);
        $this->assertDatabaseCount('procesos', 81);
        $this->assertDatabaseCount('actividades', 200);

        $this->assertSame(
            81,
            Proceso::query()->has('actividades')->count()
        );
    }

    public function test_carga_es_idempotente(): void
    {
        $this->sembrarBase();
        $this->seed(ActividadesProyectoSeeder::class);

        $this->assertDatabaseCount('actividades', 200);

        $duplicados = Actividad::query()
            ->selectRaw('id_proceso, clave, COUNT(*) as total')
            ->groupBy('id_proceso', 'clave')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        $this->assertSame(0, $duplicados);
    }

    public function test_reseed_no_reinicia_estado_fechas_asignacion_real_ni_avance(): void
    {
        $this->sembrarBase();

        $actividad = Actividad::query()
            ->where('clave', 'F6-05')
            ->firstOrFail();

        $actividad->update([
            'estado_desarrollo' => 'EN_DESARROLLO',
            'estado_verificacion' => 'PENDIENTE',
            'prioridad' => 'CRITICA',
            'peso' => 5,
            'fecha_inicio_planificada' => '2026-10-10',
            'fecha_entrega_comprometida' => '2026-11-20',
            'porcentaje_avance' => 35.50,
        ]);

        $this->seed(ActividadesProyectoSeeder::class);

        $actividad->refresh();

        $this->assertSame('EN_DESARROLLO', $actividad->estado_desarrollo);
        $this->assertSame('PENDIENTE', $actividad->estado_verificacion);
        $this->assertSame('CRITICA', $actividad->prioridad);
        $this->assertSame(5, $actividad->peso);
        $this->assertSame('2026-10-10', $actividad->fecha_inicio_planificada->format('Y-m-d'));
        $this->assertSame('2026-11-20', $actividad->fecha_entrega_comprometida->format('Y-m-d'));
        $this->assertSame('35.50', $actividad->porcentaje_avance);
    }

    public function test_preserva_responsable_recomendado_y_revision_apoyo_del_plan_fuente(): void
    {
        $this->sembrarBase();

        $this->assertDatabaseHas('actividades', [
            'clave' => 'F0-01',
            'responsable_recomendado' => 'B',
            'revision_apoyo' => 'A',
        ]);

        $this->assertDatabaseHas('actividades', [
            'clave' => 'F6-06',
            'responsable_recomendado' => 'A+B',
            'revision_apoyo' => 'A+B',
        ]);

        $this->assertDatabaseHas('actividades', [
            'clave' => 'F15-03',
            'responsable_recomendado' => 'A',
            'revision_apoyo' => 'B',
        ]);
    }

    public function test_actividades_nuevas_inician_sin_usuarios_reales_asignados(): void
    {
        $this->sembrarBase();

        $this->assertSame(
            200,
            Actividad::query()
                ->whereNull('id_responsable')
                ->whereNull('id_verificador')
                ->where('estado_desarrollo', 'POR_REALIZAR')
                ->where('estado_verificacion', 'NO_VERIFICADO')
                ->where('prioridad', 'MEDIA')
                ->where('peso', 2)
                ->where('porcentaje_avance', 0)
                ->count()
        );
    }

    public function test_cada_actividad_esta_en_el_proceso_definido_por_el_catalogo_r7(): void
    {
        $this->sembrarBase();

        $casos = [
            ['F0-P01', 'F0-01'],
            ['F6-P03', 'F6-05'],
            ['F13-P03', 'F13-06'],
            ['F15-P06', 'F15-12'],
        ];

        foreach ($casos as [$claveProceso, $claveActividad]) {
            $proceso = Proceso::query()->where('clave', $claveProceso)->firstOrFail();

            $this->assertDatabaseHas('actividades', [
                'id_proceso' => $proceso->id_proceso,
                'clave' => $claveActividad,
            ]);
        }
    }
}