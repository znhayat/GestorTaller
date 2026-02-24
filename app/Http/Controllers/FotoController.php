<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Encargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FotoController extends Controller
{
    // Ver todas las fotos (Galería)
    public function index()
    {
        $fotos = Foto::with('encargo.vehiculo.cliente')->latest()->get();
        return view('content.fotos.index', compact('fotos'));
    }

    // Formulario para subir nueva foto
    public function create()
    {
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.fotos.create', compact('encargos'));
    }

    // Guardar la foto en el disco y la BD
    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|max:2048',
            'encargo_id' => 'required|exists:encargos,id'
        ]);

        if ($request->hasFile('foto')) {
            $ruta = $request->file('foto')->store('trabajos', 'public');

            Foto::create([
                'encargo_id' => $request->encargo_id,
                'ruta' => $ruta,
                'descripcion' => $request->descripcion
            ]);
        }

        return redirect()->route('fotos.index')->with('success', 'Foto subida con éxito');
    }

    public function edit($id)
    {
        $foto = Foto::findOrFail($id);
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.fotos.edit', compact('foto', 'encargos'));
    }

    public function update(Request $request, $id)
    {
        $foto = Foto::findOrFail($id);

        if ($request->hasFile('foto')) {
            Storage::disk('public')->delete($foto->ruta);
            $foto->ruta = $request->file('foto')->store('trabajos', 'public');
        }

        $foto->encargo_id = $request->encargo_id;
        $foto->descripcion = $request->descripcion;
        $foto->save();

        return redirect()->route('fotos.index')->with('success', 'Registro actualizado.');
    }

    public function destroy($id)
    {
        $foto = Foto::findOrFail($id);
        Storage::disk('public')->delete($foto->ruta);
        $foto->delete();

        return redirect()->back()->with('success', 'Foto eliminada.');
    }
}
