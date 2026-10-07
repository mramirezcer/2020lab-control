<?php

namespace App\Http\Controllers;

use App\Models\Fase;
use Illuminate\Contracts\View\View;

class FaseController extends Controller
{
    public function show(Fase $fase): View
    {
        $fase->load([
            'responsable',
            'verificador',
            'procesos.responsable',
            'procesos.verificador',
            'procesos.actividades.responsable',
            'procesos.actividades.verificador',
        ]);

        $procesos = $fase->procesos;
        $actividades = $procesos->flatMap->actividades;

        $resumen = [
            'procesos' => $procesos->count(),
            'actividades' => $actividades->count(),
            'por_realizar' => $actividades->where('estado_desarrollo', 'POR_REALIZAR')->count(),
            'en_desarrollo' => $actividades->where('estado_desarrollo', 'EN_DESARROLLO')->count(),
            'en_verificacion' => $actividades->where('estado_desarrollo', 'EN_VERIFICACION')->count(),
            'concluidas' => $actividades->where('estado_desarrollo', 'CONCLUIDO')->count(),
            'bloqueadas' => $actividades->where('estado_desarrollo', 'BLOQUEADO')->count(),
        ];

        return view('fases.show', [
            'fase' => $fase,
            'procesos' => $procesos,
            'resumen' => $resumen,
        ]);
    }
}