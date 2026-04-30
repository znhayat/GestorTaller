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
        ]);

        // Actualizamos los datos
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return back()->with('success', 'Perfil actualitzat correctament.');
    }
}
