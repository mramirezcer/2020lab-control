<?php

namespace Tests\Feature;

use App\Models\Fase;
use App\Models\Proceso;
use Database\Seeders\FasesProyectoSeeder;
use Database\Seeders\ProcesosProyectoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class P11CProcesosMaestrosTest extends TestCase
{
    use RefreshDatabase;

    private function sembrarBase(): void
    {
        $this->seed(FasesProyectoSeeder::class);
        $this->seed(ProcesosProyectoSeeder::class);
    }

    public function test_carga_exactamente_81_procesos_en_16_fases(): void
    {
        $this->sembrarBase();

        $this->assertDatabaseCount('fases', 16);
        $this->assertDatabaseCount('procesos', 81);
        $this->assertDatabaseCount('actividades', 0);

        $esperados = [
            'F0'=>6,'F1'=>5,'F2'=>7,'F3'=>3,
            'F4'=>5,'F5'=>4,'F6'=>6,'F7'=>4,
            'F8'=>5,'F9'=>5,'F10'=>4,'F11'=>5,
            'F12'=>6,'F13'=>5,'F14'=>5,'F15'=>6,
        ];

        foreach ($esperados as $claveFase => $total) {
            $fase = Fase::query()->where('clave', $claveFase)->firstOrFail();

            $this->assertSame(
                $total,
                Proceso::query()->where('id_fase', $fase->id_fase)->count(),
                $claveFase
            );
        }
    }

    public function test_carga_es_idempotente(): void
    {
        $this->sembrarBase();
        $this->seed(ProcesosProyectoSeeder::class);

        $this->assertDatabaseCount('procesos', 81);

        $duplicados = Proceso::query()
            ->selectRaw('id_fase, clave, COUNT(*) as total')
            ->groupBy('id_fase', 'clave')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        $this->assertSame(0, $duplicados);
    }

    public function test_reseed_no_reinicia_estado_fechas_responsables_ni_avance(): void
    {
        $this->sembrarBase();

        $proceso = Proceso::query()
            ->where('clave', 'F6-P03')
            ->firstOrFail();

        $proceso->update([
            'estado_desarrollo' => 'EN_DESARROLLO',
            'estado_verificacion' => 'PENDIENTE',
            'prioridad' => 'CRITICA',
            'fecha_inicio_planificada' => '2026-10-10',
            'fecha_entrega_comprometida' => '2026-11-20',
            'porcentaje_avance' => 42.50,
        ]);

        $this->seed(ProcesosProyectoSeeder::class);

        $proceso->refresh();

        $this->assertSame('EN_DESARROLLO', $proceso->estado_desarrollo);
        $this->assertSame('PENDIENTE', $proceso->estado_verificacion);
        $this->assertSame('CRITICA', $proceso->prioridad);
        $this->assertSame('2026-10-10', $proceso->fecha_inicio_planificada->format('Y-m-d'));
        $this->assertSame('2026-11-20', $proceso->fecha_entrega_comprometida->format('Y-m-d'));
        $this->assertSame('42.50', $proceso->porcentaje_avance);
    }

    public function test_agrupaciones_criticas_existen_en_su_fase_correcta(): void
    {
        $this->sembrarBase();

        $casos = [
            ['F0', 'F0-P01', 'Gobierno técnico y plataforma de desarrollo'],
            ['F6', 'F6-P03', 'Interfaces de analizadores'],
            ['F13', 'F13-P03', 'Intercambio operativo e idempotencia'],
            ['F15', 'F15-P06', 'Independencia del módulo adicional'],
        ];

        foreach ($casos as [$faseClave, $procesoClave, $nombre]) {
            $fase = Fase::query()->where('clave', $faseClave)->firstOrFail();

            $this->assertDatabaseHas('procesos', [
                'id_fase' => $fase->id_fase,
                'clave' => $procesoClave,
                'nombre' => $nombre,
            ]);
        }
    }

    public function test_procesos_nuevos_inician_en_estado_base_sin_responsables(): void
    {
        $this->sembrarBase();

        $this->assertSame(
            81,
            Proceso::query()
                ->where('estado_desarrollo', 'POR_REALIZAR')
                ->where('estado_verificacion', 'NO_VERIFICADO')
                ->where('prioridad', 'MEDIA')
                ->where('porcentaje_avance', 0)
                ->whereNull('id_responsable')
                ->whereNull('id_verificador')
                ->count()
        );
    }
}