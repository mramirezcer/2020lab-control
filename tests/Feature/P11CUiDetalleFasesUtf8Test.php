<?php

namespace Tests\Feature;

use App\Models\Fase;
use App\Models\Rol;
use App\Models\User;
use Database\Seeders\ActividadesProyectoSeeder;
use Database\Seeders\FasesProyectoSeeder;
use Database\Seeders\ProcesosProyectoSeeder;
use Database\Seeders\RolesProyectoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class P11CUiDetalleFasesUtf8Test extends TestCase
{
    use RefreshDatabase;

    private function crearLider(): User
    {
        $this->seed(RolesProyectoSeeder::class);

        $rol = Rol::query()
            ->where('clave', 'LIDER_PROYECTO')
            ->firstOrFail();

        return User::factory()->create([
            'id_rol' => $rol->id_rol,
            'activo' => true,
        ]);
    }

    private function sembrarEstructura(): void
    {
        $this->seed(FasesProyectoSeeder::class);
        $this->seed(ProcesosProyectoSeeder::class);
        $this->seed(ActividadesProyectoSeeder::class);
    }

    public function test_login_no_contiene_mojibake_y_conserva_etiquetas_espanolas(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertDontSee('Ã', false);
        $response->assertSee('Certificaci&oacute;n', false);
        $response->assertSee('Correo electr&oacute;nico', false);
        $response->assertSee('Contrase&ntilde;a', false);
        $response->assertSee('Recordar sesi&oacute;n', false);
    }

    public function test_dashboard_ofrece_enlace_a_detalle_de_cada_fase(): void
    {
        $this->sembrarEstructura();
        $usuario = $this->crearLider();

        $this->actingAs($usuario)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Ver detalle')
            ->assertSee(route('fases.show', 'F0'), false)
            ->assertSee(route('fases.show', 'F15'), false);
    }

    public function test_detalle_f0_muestra_sus_procesos_y_27_actividades(): void
    {
        $this->sembrarEstructura();
        $usuario = $this->crearLider();

        $fase = Fase::query()->where('clave', 'F0')->firstOrFail();

        $this->actingAs($usuario)
            ->get(route('fases.show', $fase->clave))
            ->assertOk()
            ->assertSee('F0-P01')
            ->assertSee('Gobierno técnico y plataforma de desarrollo')
            ->assertSee('F0-01')
            ->assertSee('Crear repositorio GitHub, proyecto Laravel 13 y estrategia de ramas')
            ->assertSee('27');
    }

    public function test_detalle_f15_muestra_6_procesos_y_12_actividades(): void
    {
        $this->sembrarEstructura();
        $usuario = $this->crearLider();

        $fase = Fase::query()->where('clave', 'F15')->firstOrFail();

        $this->actingAs($usuario)
            ->get(route('fases.show', $fase->clave))
            ->assertOk()
            ->assertSee('F15-P06')
            ->assertSee('F15-12')
            ->assertSee('12');
    }

    public function test_invitado_no_puede_abrir_detalle_de_fase(): void
    {
        $this->sembrarEstructura();

        $this->get('/fases/F0')
            ->assertRedirect('/login');
    }

    public function test_vistas_nuevas_no_contienen_patrones_mojibake(): void
    {
        $archivos = [
            resource_path('views/auth/login.blade.php'),
            resource_path('views/dashboard.blade.php'),
            resource_path('views/fases/show.blade.php'),
        ];

        foreach ($archivos as $archivo) {
            $contenido = file_get_contents($archivo);

            $this->assertNotFalse($contenido);
            $this->assertStringNotContainsString('Ã', $contenido);
            $this->assertStringNotContainsString('Â', $contenido);
        }
    }
}