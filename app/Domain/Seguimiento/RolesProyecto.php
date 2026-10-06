<?php

namespace App\Domain\Seguimiento;

final class RolesProyecto
{
    public const DIRECTOR = 'DIRECTOR';
    public const LIDER_PROYECTO = 'LIDER_PROYECTO';
    public const PROGRAMADOR_B = 'PROGRAMADOR_B';
    public const ADMINISTRADOR = 'ADMINISTRADOR';

    public const TODOS = [
        self::DIRECTOR,
        self::LIDER_PROYECTO,
        self::PROGRAMADOR_B,
        self::ADMINISTRADOR,
    ];

    private function __construct()
    {
    }
}