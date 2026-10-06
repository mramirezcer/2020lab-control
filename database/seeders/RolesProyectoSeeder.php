<?php

namespace Database\Seeders;

use App\Domain\Seguimiento\RolesProyecto;
use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolesProyectoSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'clave' => RolesProyecto::DIRECTOR,
                'nombre' => 'Director',
                'descripcion' => 'Consulta ejecutiva, revisiones y observaciones. No modifica estados oficiales.',
            ],
            [
                'clave' => RolesProyecto::LIDER_PROYECTO,
                'nombre' => 'Lider del Proyecto / Programador A',
                'descripcion' => 'Responsable del estado oficial, fechas, avance y cierre tecnico del proyecto.',
            ],
            [
                'clave' => RolesProyecto::PROGRAMADOR_B,
                'nombre' => 'Programador B',
                'descripcion' => 'Actualiza trabajo asignado, evidencias y participa en verificacion tecnica.',
            ],
            [
                'clave' => RolesProyecto::ADMINISTRADOR,
                'nombre' => 'Administrador',
                'descripcion' => 'Administra usuarios y configuracion del portal.',
            ],
        ];

        foreach ($roles as $rol) {
            Rol::query()->updateOrCreate(
                ['clave' => $rol['clave']],
                $rol + ['activo' => true]
            );
        }
    }
}