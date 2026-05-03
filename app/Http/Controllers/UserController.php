<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Asegurar que solo administradores pueden acceder.
     */
    public function __construct()
    {
        // Alternativa a middleware en rutas. Si auth fails o no es admin, fuera.
    }

    /**
     * Mostrar lista de usuarios.
     */
    public function index()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Acceso Denegado. Solo administradores.');
        }

        $usuarios = User::orderBy('created_at', 'desc')->paginate(10);
        return view('content.users.index', compact('usuarios'));
    }

    /**
     * Actualizar estado o rol del usuario.
     */
    public function update(Request $request, User $usuario)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Acceso Denegado. Solo administradores.');
        }

        // Impedir que un admin se quite el rol a sí mismo si es el único
        if ($usuario->id === auth()->id() && $request->role !== 'admin') {
            return back()->with('error', 'No puedes quitarte los permisos de administrador a ti mismo.');
        }

        $request->validate([
            'role' => 'required|in:admin,user',
            'is_approved' => 'required|boolean',
            'password' => 'nullable|string|min:6|confirmed'
        ], [
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.symbols' => 'La contraseña debe contener al menos un símbolo.',
        ]);

        $data = [
            'role' => $request->role,
            'is_approved' => $request->is_approved,
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $usuario->update($data);

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Eliminar usuario (baja).
     */
    public function destroy(User $usuario)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Acceso Denegado. Solo administradores.');
        }

        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();
        return back()->with('success', 'Usuario eliminado permanentemente.');
    }
}
