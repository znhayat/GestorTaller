<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     */
    public function edit()
    {
        $user = Auth::user();
        return view('content.profile.edit', compact('user'));
    }

    /**
     * Procesar la actualización
     */
    public function update(Request $request)
    {
        /** @var User $user */ // Esto quita el error visual en VS Code
        $user = Auth::user();

        // Si por algún motivo no hay usuario, redirigir al login
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()->symbols()],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo ya está registrado.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters' => 'La contraseña debe contener al menos una letra.',
            'password.numbers' => 'La contraseña debe contener al menos un número.',
            'password.symbols' => 'La contraseña debe contener al menos un símbolo (ej: @, #, $, !).',
        ]);

        // Actualizamos los datos
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

    }

    /**
     * Dar de baja la cuenta (Eliminar)
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        // Seguridad: Si es admin, comprobamos que no sea el único
        if ($user->role === 'admin') {
            $otrosAdmins = User::where('role', 'admin')->where('id', '!=', $user->id)->count();
            if ($otrosAdmins === 0) {
                return back()->withErrors(['email' => 'No puedes eliminar tu cuenta porque eres el único administrador del sistema.']);
            }
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Tu cuenta ha sido eliminada correctamente.');
    }
}
