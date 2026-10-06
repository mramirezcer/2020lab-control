<?php

namespace Tests\Feature;

use App\Domain\Seguimiento\PermisosProyecto;
use App\Domain\Seguimiento\RolesProyecto;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class P11BAuthRolesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            RolesProyecto::DIRECTOR => 'Director',
            RolesProyecto::LIDER_PROYECTO => 'Lider del Proyecto / Programador A',
            RolesProyecto::PROGRAMADOR_B => 'Programador B',
            RolesProyecto::ADMINISTRADOR => 'Administrador',
        ] as $clave => $nombre) {
            Rol::query()->create([
                'clave' => $clave,
                'nombre' => $nombre,
                'activo' => true,
            ]);
        }
    }

    public function test_invitado_es_redirigido_al_login(): void
    {
        $this->get('/dashboard')
            ->assertRedirect('/login');
    }

    public function test_usuario_activo_con_rol_puede_iniciar_sesion(): void
    {
        $rol = Rol::query()->where('clave', RolesProyecto::LIDER_PROYECTO)->firstOrFail();

        $usuario = User::factory()->create([
            'id_rol' => $rol->id_rol,
            'activo' => true,
            'password' => 'Secret123!',
        ]);

        $this->post('/login', [
            'email' => $usuario->email,
            'password' => 'Secret123!',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($usuario);
    }

    public function test_usuario_inactivo_no_conserva_sesion(): void
    {
        $rol = Rol::query()->where('clave', RolesProyecto::PROGRAMADOR_B)->firstOrFail();

        $usuario = User::factory()->create([
            'id_rol' => $rol->id_rol,
            'activo' => false,
            'password' => 'Secret123!',
        ]);

        $this->post('/login', [
            'email' => $usuario->email,
            'password' => 'Secret123!',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_director_puede_revisar_pero_no_cambiar_estado(): void
    {
        $rol = Rol::query()->where('clave', RolesProyecto::DIRECTOR)->firstOrFail();

        $director = User::factory()->create([
            'id_rol' => $rol->id_rol,
            'activo' => true,
        ]);

        $this->actingAs($director);

        $this->assertTrue(Gate::allows(PermisosProyecto::REVISAR_COMO_DIRECTOR));
        $this->assertTrue(Gate::allows(PermisosProyecto::EMITIR_OBSERVACION_DIRECTOR));
        $this->assertFalse(Gate::allows(PermisosProyecto::CAMBIAR_ESTADO_PROYECTO));

        $this->get('/director')->assertOk();
        $this->get('/lider')->assertForbidden();
    }

    public function test_lider_puede_cambiar_estado_y_no_entra_como_director(): void
    {
        $rol = Rol::query()->where('clave', RolesProyecto::LIDER_PROYECTO)->firstOrFail();

        $lider = User::factory()->create([
            'id_rol' => $rol->id_rol,
            'activo' => true,
        ]);

        $this->actingAs($lider);

        $this->assertTrue(Gate::allows(PermisosProyecto::CAMBIAR_ESTADO_PROYECTO));
        $this->assertFalse(Gate::allows(PermisosProyecto::REVISAR_COMO_DIRECTOR));

        $this->get('/lider')->assertOk();
        $this->get('/director')->assertForbidden();
    }

    public function test_programador_b_actualiza_trabajo_asignado_sin_cambiar_estado_oficial(): void
    {
        $rol = Rol::query()->where('clave', RolesProyecto::PROGRAMADOR_B)->firstOrFail();

        $programador = User::factory()->create([
            'id_rol' => $rol->id_rol,
            'activo' => true,
        ]);

        $this->actingAs($programador);

        $this->assertTrue(Gate::allows(PermisosProyecto::ACTUALIZAR_TRABAJO_ASIGNADO));
        $this->assertFalse(Gate::allows(PermisosProyecto::CAMBIAR_ESTADO_PROYECTO));

        $this->get('/programador-b')->assertOk();
        $this->get('/lider')->assertForbidden();
    }

    public function test_administrador_administra_usuarios_sin_asumir_facultad_del_lider(): void
    {
        $rol = Rol::query()->where('clave', RolesProyecto::ADMINISTRADOR)->firstOrFail();

        $admin = User::factory()->create([
            'id_rol' => $rol->id_rol,
            'activo' => true,
        ]);

        $this->actingAs($admin);

        $this->assertTrue(Gate::allows(PermisosProyecto::ADMINISTRAR_USUARIOS));
        $this->assertFalse(Gate::allows(PermisosProyecto::CAMBIAR_ESTADO_PROYECTO));

        $this->get('/administracion')->assertOk();
        $this->get('/lider')->assertForbidden();
    }
}