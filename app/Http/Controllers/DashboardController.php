<?php

namespace App\Http\Controllers;

use App\Models\Fase;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $fases = Fase::query()
            ->orderBy('orden')
            ->get();

        $resumen = [
            'total' => $fases->count(),
            'por_realizar' => $fases->where('estado_desarrollo', 'POR_REALIZAR')->count(),
            'en_desarrollo' => $fases->where('estado_desarrollo', 'EN_DESARROLLO')->count(),
            'en_verificacion' => $fases->where('estado_desarrollo', 'EN_VERIFICACION')->count(),
            'concluidas' => $fases->where('estado_desarrollo', 'CONCLUIDO')->count(),
            'bloqueadas' => $fases->where('estado_desarrollo', 'BLOQUEADO')->count(),
        ];

        return view('dashboard', [
            'fases' => $fases,
            'resumen' => $resumen,
        ]);
    }
}