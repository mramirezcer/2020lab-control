<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2020Lab V2 - Control de Desarrollo y Certificaci&oacute;n</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <main class="flex min-h-screen items-center justify-center px-6 py-10">
        <section class="w-full max-w-xl rounded-3xl border border-slate-700 bg-slate-900 p-10 shadow-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.30em] text-cyan-400">2020LAB V2</p>

            <h1 class="mt-4 text-3xl font-bold leading-tight text-white">
                Control de Desarrollo y Certificaci&oacute;n
            </h1>

            <p class="mt-3 text-base text-slate-400">
                Acceso interno autorizado.
            </p>

            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-red-700 bg-red-950/60 px-4 py-3 text-sm text-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-6">
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-white">
                        Correo electr&oacute;nico
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        autofocus
                        class="w-full rounded-xl border border-cyan-400 bg-slate-100 px-5 py-4 text-slate-950 outline-none ring-0"
                    >
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-white">
                        Contrase&ntilde;a
                    </label>

                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-slate-100 px-5 py-4 text-slate-950 outline-none ring-0"
                    >
                </div>

                <label class="flex items-center gap-3 text-sm text-slate-300">
                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                        class="h-4 w-4 rounded border-slate-500"
                    >
                    <span>Recordar sesi&oacute;n</span>
                </label>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-cyan-500 px-5 py-4 font-semibold text-slate-950 transition hover:bg-cyan-400"
                >
                    Ingresar
                </button>
            </form>
        </section>
    </main>
</body>
</html>