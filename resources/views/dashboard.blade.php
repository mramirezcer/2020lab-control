<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2020Lab V2 - Seguimiento ejecutivo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="min-h-screen">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-600">2020LAB V2</p>
                    <h1 class="mt-1 text-2xl font-semibold">Control de Desarrollo y Certificaci&oacute;n</h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->rol?->nombre ?? 'Sin rol' }}</p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium hover:bg-slate-50">
                            Cerrar sesi&oacute;n
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 py-8">
            <section>
                <p class="text-sm font-medium text-slate-500">Seguimiento ejecutivo</p>
                <h2 class="mt-1 text-3xl font-semibold">Fases del proyecto</h2>
                <p class="mt-2 max-w-3xl text-sm text-slate-600">
                    Seleccione una fase para consultar sus procesos y actividades.
                </p>

                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-6">
                    @foreach ([
                        ['Total', $resumen['total']],
                        ['Por realizar', $resumen['por_realizar']],
                        ['En desarrollo', $resumen['en_desarrollo']],
                        ['Verificaci&oacute;n', $resumen['en_verificacion']],
                        ['Concluidas', $resumen['concluidas']],
                        ['Bloqueadas', $resumen['bloqueadas']],
                    ] as [$etiqueta, $valor])
                        <article class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                            <p class="text-xs uppercase tracking-wide text-slate-500">{!! $etiqueta !!}</p>
                            <p class="mt-2 text-3xl font-semibold">{{ $valor }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="mt-8 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h3 class="font-semibold">Alcance maestro F0-F15</h3>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $fases->count() }} fases registradas. Cada fase tiene una vista detallada.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <th class="px-5 py-3">Fase</th>
                                <th class="px-5 py-3">Descripci&oacute;n</th>
                                <th class="px-5 py-3">Estado</th>
                                <th class="px-5 py-3">Verificaci&oacute;n</th>
                                <th class="px-5 py-3">Prioridad</th>
                                <th class="px-5 py-3">Avance</th>
                                <th class="px-5 py-3">Entrega</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($fases as $fase)
                                <tr class="align-top hover:bg-slate-50">
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <div class="font-semibold">{{ $fase->clave }}</div>
                                        <div class="mt-1 max-w-xs text-slate-700">{{ $fase->nombre }}</div>
                                    </td>
                                    <td class="max-w-xl px-5 py-4 text-slate-600">{{ $fase->descripcion }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">{{ str_replace('_', ' ', $fase->estado_desarrollo) }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">{{ str_replace('_', ' ', $fase->estado_verificacion) }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">{{ $fase->prioridad }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">{{ number_format((float) $fase->porcentaje_avance, 0) }}%</td>
                                    <td class="whitespace-nowrap px-5 py-4">{{ $fase->fecha_entrega_comprometida?->format('d/m/Y') ?? 'Por definir' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <a
                                            href="{{ route('fases.show', $fase->clave) }}"
                                            class="inline-flex rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700"
                                        >
                                            Ver detalle
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>