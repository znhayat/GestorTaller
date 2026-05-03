<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('content.authentications.auth-login-basic');
    }

    public function login(Request $request)
    {
        // 1. Validar datos
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Intentar autenticar
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
            'password' => ['required', 'string', \Illuminate\Validation\Rules\Password::min(8)->letters()->numbers()->symbols(), 'confirmed'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Por favor, introduce un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters' => 'La contraseña debe incluir letras.',
            'password.numbers' => 'La contraseña debe incluir números.',
            'password.symbols' => 'La contraseña debe incluir símbolos.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // Verificar si es el primer usuario (no hay ningun usuario en la base de datos)
        $isFirstUser = User::count() == 0;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_approved' => $isFirstUser, // El primer usuario se aprueba automaticamente
            'role' => $isFirstUser ? 'admin' : 'user', // El primer usuario es admin
        ]);

        if ($isFirstUser) {
            // El primer usuario inicia sesion automaticamente
            Auth::login($user);
            return redirect()->route('dashboard-analytics')->with('success', 'Bienvenido. Eres el administrador principal.');
        }

        return redirect()->route('login')->with('success', 'Registro completado. Tu cuenta debe ser aprobada por un administrador antes de poder acceder.');
    }

    // Metodo para que el admin pueda aprobar usuarios (añadir a otro controller o aqui mismo)
    public function pendingUsers()
    {
        $pendingUsers = User::where('is_approved', false)->get();
        return view('content.users.pending', compact('pendingUsers'));
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_approved = true;
        $user->save();

        return redirect()->back()->with('success', 'Usuario aprobado correctamente');
    }
}
