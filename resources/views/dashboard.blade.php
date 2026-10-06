<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | 2020Lab Control</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <header class="border-b border-slate-800 bg-slate-900">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-400">2020Lab V2</p>
                <h1 class="text-xl font-bold">Control de Desarrollo y CertificaciÃ³n</h1>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="rounded-lg border border-slate-700 px-4 py-2 text-sm hover:bg-slate-800">
                    Cerrar sesiÃ³n
                </button>
            </form>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-10">
        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-8">
            <p class="text-sm text-slate-400">SesiÃ³n autenticada</p>
            <h2 class="mt-2 text-2xl font-bold">{{ auth()->user()->name }}</h2>

            <dl class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="rounded-xl bg-slate-950 p-4">
                    <dt class="text-xs uppercase tracking-wider text-slate-500">Correo</dt>
                    <dd class="mt-1">{{ auth()->user()->email }}</dd>
                </div>

                <div class="rounded-xl bg-slate-950 p-4">
                    <dt class="text-xs uppercase tracking-wider text-slate-500">Rol</dt>
                    <dd class="mt-1 font-semibold text-cyan-300">
                        {{ auth()->user()->rol?->nombre ?? 'Sin rol' }}
                    </dd>
                </div>
            </dl>

            <div class="mt-8 rounded-xl border border-amber-800/60 bg-amber-950/30 p-4 text-sm text-amber-100">
                Dashboard base P11B. El contenido ejecutivo de fases y avance se implementarÃ¡ en gates posteriores.
            </div>
        </div>
    </main>
</body>
</html>