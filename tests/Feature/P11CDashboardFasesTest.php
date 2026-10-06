<?php

namespace Tests\Feature;

use App\Models\Fase;
use App\Models\Rol;
use App\Models\User;
use Database\Seeders\FasesProyectoSeeder;
use Database\Seeders\RolesProyectoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class P11CDashboardFasesTest extends TestCase
{
    use RefreshDatabase;

    private function crearLider(): User
    {
        $this->seed(RolesProyectoSeeder::class);

        $rol = Rol::query()->where('clave', 'LIDER_PROYECTO')->firstOrFail();

        return User::factory()->create([
            'id_rol' => $rol->id_rol,
            'activo' => true,
        ]);
    }

    public function test_invitado_no_puede_ver_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_lider_visualiza_las_16_fases_en_orden(): void
    {
        $this->seed(FasesProyectoSeeder::class);
        $usuario = $this->crearLider();

        $this->actingAs($usuario)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Control de Desarrollo y CertificaciÃ³n')
            ->assertSee('Alcance maestro F0â€“F15')
            ->assertSee('16 fases registradas')
            ->assertSeeInOrder([
                'F0',
                'Fundaciones, Arquitectura y Modelo de Datos',
                'F1',
                'Alta de laboratorio',
                'F15',
                'Calidad e Inventario - Modulo adicional',
            ]);
    }

    public function test_dashboard_calcula_resumen_de_estados(): void
    {
        $this->seed(FasesProyectoSeeder::class);

        Fase::query()->where('clave', 'F0')->update([
            'estado_desarrollo' => 'EN_DESARROLLO',
        ]);

        Fase::query()->where('clave', 'F1')->update([
            'estado_desarrollo' => 'CONCLUIDO',
            'porcentaje_avance' => 100,
        ]);

        $usuario = $this->crearLider();

        $this->actingAs($usuario)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewHas('resumen', function (array $resumen): bool {
                return $resumen['total'] === 16
                    && $resumen['por_realizar'] === 14
                    && $resumen['en_desarrollo'] === 1
                    && $resumen['concluidas'] === 1;
            });
    }
}