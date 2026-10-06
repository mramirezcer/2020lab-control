<?php

namespace App\Domain\Seguimiento;

final class EstadosProyecto
{
    public const DESARROLLO = [
        'POR_REALIZAR',
        'EN_DESARROLLO',
        'EN_VERIFICACION',
        'CONCLUIDO',
        'BLOQUEADO',
    ];

    public const VERIFICACION = [
        'NO_VERIFICADO',
        'PENDIENTE',
        'VERIFICADO',
        'CON_OBSERVACIONES',
    ];

    public const PRIORIDADES = [
        'BAJA' => 1,
        'MEDIA' => 2,
        'ALTA' => 3,
        'CRITICA' => 5,
    ];

    public const OBSERVACION_DIRECTOR = [
        'ABIERTA',
        'EN_ANALISIS',
        'ACEPTADA',
        'EN_ATENCION',
        'ATENDIDA',
        'VERIFICADA',
        'NO_APLICA',
        'DIFERIDA',
    ];

    public const REVISION_DIRECTOR = [
        'REVISADA_SIN_OBSERVACIONES',
        'REVISADA_CON_OBSERVACIONES',
        'REVISION_INFORMATIVA',
    ];

    private function __construct()
    {
    }
}