<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $fase->clave }} - {{ $fase->nombre }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="min-h-screen">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5">
                <div>
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-cyan-700 hover:underline">
                        &larr; Volver al tablero ejecutivo
                    </a>
                    <h1 class="mt-2 text-2xl font-semibold">
                        {{ $fase->clave }} - {{ $fase->nombre }}
                    </h1>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium hover:bg-slate-50">
                        Cerrar sesi&oacute;n
                    </button>
                </form>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 py-8">
            <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-700">
                            Detalle de fase
                        </p>
                        <h2 class="mt-2 text-3xl font-semibold">{{ $fase->nombre }}</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $fase->descripcion }}</p>
                    </div>

                    <dl class="grid min-w-72 grid-cols-2 gap-x-6 gap-y-3 text-sm">
                        <dt class="text-slate-500">Estado</dt>
                        <dd class="font-medium">{{ str_replace('_', ' ', $fase->estado_desarrollo) }}</dd>

                        <dt class="text-slate-500">Verificaci&oacute;n</dt>
                        <dd class="font-medium">{{ str_replace('_', ' ', $fase->estado_verificacion) }}</dd>

                        <dt class="text-slate-500">Prioridad</dt>
                        <dd class="font-medium">{{ $fase->prioridad }}</dd>

                        <dt class="text-slate-500">Avance</dt>
                        <dd class="font-medium">{{ number_format((float) $fase->porcentaje_avance, 0) }}%</dd>

                        <dt class="text-slate-500">Responsable</dt>
                        <dd class="font-medium">{{ $fase->responsable?->name ?? 'Sin asignar' }}</dd>

                        <dt class="text-slate-500">Verificador</dt>
                        <dd class="font-medium">{{ $fase->verificador?->name ?? 'Sin asignar' }}</dd>

                        <dt class="text-slate-500">Entrega comprometida</dt>
                        <dd class="font-medium">{{ $fase->fecha_entrega_comprometida?->format('d/m/Y') ?? 'Por definir' }}</dd>
                    </dl>
                </div>
            </section>

            <section class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-7">
                @foreach ([
                    ['Procesos', $resumen['procesos']],
                    ['Actividades', $resumen['actividades']],
                    ['Por realizar', $resumen['por_realizar']],
                    ['En desarrollo', $resumen['en_desarrollo']],
                    ['En verificaci&oacute;n', $resumen['en_verificacion']],
                    ['Concluidas', $resumen['concluidas']],
                    ['Bloqueadas', $resumen['bloqueadas']],
                ] as [$etiqueta, $valor])
                    <article class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                        <p class="text-xs uppercase tracking-wide text-slate-500">{!! $etiqueta !!}</p>
                        <p class="mt-2 text-2xl font-semibold">{{ $valor }}</p>
                    </article>
                @endforeach
            </section>

            <section class="mt-8 space-y-5">
                @forelse ($procesos as $proceso)
                    <article class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
                        <header class="border-b border-slate-200 px-5 py-4">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-cyan-700">
                                        {{ $proceso->clave }}
                                    </p>
                                    <h3 class="mt-1 text-lg font-semibold">{{ $proceso->nombre }}</h3>
                                    @if ($proceso->descripcion)
                                        <p class="mt-2 text-sm text-slate-600">{{ $proceso->descripcion }}</p>
                                    @endif
                                </div>

                                <div class="text-sm text-slate-600">
                                    <div>Estado: <strong class="text-slate-900">{{ str_replace('_', ' ', $proceso->estado_desarrollo) }}</strong></div>
                                    <div>Avance: <strong class="text-slate-900">{{ number_format((float) $proceso->porcentaje_avance, 0) }}%</strong></div>
                                </div>
                            </div>
                        </header>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-sm">
                                <thead class="bg-slate-50">
                                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        <th class="px-5 py-3">Actividad</th>
                                        <th class="px-5 py-3">Responsable recomendado</th>
                                        <th class="px-5 py-3">Revisi&oacute;n / apoyo</th>
                                        <th class="px-5 py-3">Estado</th>
                                        <th class="px-5 py-3">Verificaci&oacute;n</th>
                                        <th class="px-5 py-3">Prioridad</th>
                                        <th class="px-5 py-3">Peso</th>
                                        <th class="px-5 py-3">Avance</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($proceso->actividades as $actividad)
                                        <tr class="align-top hover:bg-slate-50">
                                            <td class="px-5 py-4">
                                                <div class="font-semibold">{{ $actividad->clave }}</div>
                                                <div class="mt-1 max-w-xl text-slate-700">{{ $actividad->nombre }}</div>
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-4">{{ $actividad->responsable_recomendado ?? '-' }}</td>
                                            <td class="whitespace-nowrap px-5 py-4">{{ $actividad->revision_apoyo ?? '-' }}</td>
                                            <td class="whitespace-nowrap px-5 py-4">{{ str_replace('_', ' ', $actividad->estado_desarrollo) }}</td>
                                            <td class="whitespace-nowrap px-5 py-4">{{ str_replace('_', ' ', $actividad->estado_verificacion) }}</td>
                                            <td class="whitespace-nowrap px-5 py-4">{{ $actividad->prioridad }}</td>
                                            <td class="whitespace-nowrap px-5 py-4">{{ $actividad->peso }}</td>
                                            <td class="whitespace-nowrap px-5 py-4">{{ number_format((float) $actividad->porcentaje_avance, 0) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl bg-white p-8 text-center text-slate-500 shadow-sm ring-1 ring-slate-200">
                        La fase todav&iacute;a no tiene procesos registrados.
                    </div>
                @endforelse
            </section>
        </main>
    </div>
</body>
</html>