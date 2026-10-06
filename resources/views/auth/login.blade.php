<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso | 2020Lab Control</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <main class="mx-auto flex min-h-screen max-w-6xl items-center justify-center px-6 py-12">
        <section class="w-full max-w-md rounded-2xl border border-slate-800 bg-slate-900 p-8 shadow-2xl">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-400">2020Lab V2</p>
                <h1 class="mt-2 text-2xl font-bold">Control de Desarrollo y CertificaciÃ³n</h1>
                <p class="mt-2 text-sm text-slate-400">Acceso interno autorizado.</p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-lg border border-red-800 bg-red-950/50 p-4 text-sm text-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium">Correo electrÃ³nico</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 outline-none focus:border-cyan-500"
                    >
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium">ContraseÃ±a</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 outline-none focus:border-cyan-500"
                    >
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <input type="checkbox" name="remember" value="1">
                    Recordar sesiÃ³n
                </label>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-cyan-500 px-4 py-3 font-semibold text-slate-950 transition hover:bg-cyan-400"
                >
                    Ingresar
                </button>
            </form>
        </section>
    </main>
</body>
</html>