<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $recordarme = $request->boolean('remember');

        if (! Auth::attempt($credenciales, $recordarme)) {
            return back()
                ->withErrors(['email' => 'Las credenciales no son correctas.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $usuario = $request->user();

        if (! $usuario || ! $usuario->activo || ! $usuario->rol) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'La cuenta no esta habilitada para acceder al portal.'])
                ->onlyInput('email');
        }

        $usuario->forceFill([
            'ultimo_acceso_at' => now(),
        ])->save();

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}