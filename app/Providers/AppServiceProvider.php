<?php

namespace App\Providers;

use App\Domain\Seguimiento\PermisosProyecto;
use App\Domain\Seguimiento\RolesProyecto;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define(
            PermisosProyecto::CAMBIAR_ESTADO_PROYECTO,
            fn (User $usuario): bool => $usuario->tieneRol(RolesProyecto::LIDER_PROYECTO)
        );

        Gate::define(
            PermisosProyecto::ADMINISTRAR_USUARIOS,
            fn (User $usuario): bool => $usuario->tieneRol(RolesProyecto::ADMINISTRADOR)
        );

        Gate::define(
            PermisosProyecto::ACTUALIZAR_TRABAJO_ASIGNADO,
            fn (User $usuario): bool => $usuario->tieneRol(
                RolesProyecto::LIDER_PROYECTO,
                RolesProyecto::PROGRAMADOR_B
            )
        );

        Gate::define(
            PermisosProyecto::REVISAR_COMO_DIRECTOR,
            fn (User $usuario): bool => $usuario->tieneRol(RolesProyecto::DIRECTOR)
        );

        Gate::define(
            PermisosProyecto::EMITIR_OBSERVACION_DIRECTOR,
            fn (User $usuario): bool => $usuario->tieneRol(RolesProyecto::DIRECTOR)
        );
    }
}