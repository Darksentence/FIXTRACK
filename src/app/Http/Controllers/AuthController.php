<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Ingresa tu correo electrónico.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'password.required' => 'Ingresa tu contraseña.',
        ]);

        if (! Auth::attempt($credenciales, $request->boolean('remember'))) {
            // Mensaje genérico: no revela si el correo existe o no.
            return back()
                ->withErrors(['email' => 'Las credenciales no son correctas.'])
                ->onlyInput('email');
        }

        // Evita la fijación de sesión: nuevo ID de sesión tras autenticarse.
        $request->session()->regenerate();

        return redirect()->intended(route('panel'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
