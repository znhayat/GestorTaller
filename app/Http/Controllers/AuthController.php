<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('content.authentications.auth-login-basic'); // Ruta a tu vista del template
    }

    public function login(Request $request)
    {
        // 1. Validar datos
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Intentar autenticar (Laravel se encarga del Hash)
        if (Auth::attempt($credentials)) {
            // Comprobar si está aprobado
            if (!Auth::user()->is_approved) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tu cuenta está pendiente de aprobación por parte de un administrador.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->route('dashboard-analytics');
        }

        // 3. Si falla, volver con error
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }


    public function showRegister()
    {
        return view('content.authentications.auth-register-basic'); 
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registro completado. Tu cuenta debe ser aprobada por un administrador antes de poder acceder.');
    }
}
