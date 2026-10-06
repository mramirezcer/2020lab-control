<?php

namespace App\Domain\Seguimiento;

final class PermisosProyecto
{
    public const CAMBIAR_ESTADO_PROYECTO = 'cambiar-estado-proyecto';
    public const ADMINISTRAR_USUARIOS = 'administrar-usuarios';
    public const ACTUALIZAR_TRABAJO_ASIGNADO = 'actualizar-trabajo-asignado';
    public const REVISAR_COMO_DIRECTOR = 'revisar-como-director';
    public const EMITIR_OBSERVACION_DIRECTOR = 'emitir-observacion-director';

    private function __construct()
    {
    }
}