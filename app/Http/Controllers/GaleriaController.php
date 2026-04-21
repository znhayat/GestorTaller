<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriaController extends Controller
{
    /**
     * Muestra la tabla del CMS para la galería.
     */
    public function index()
    {
        $fotos = Foto::where('es_publica', true)->latest()->get();
        return view('content.galeria.index', compact('fotos'));
    }

    /**
     * Guarda una nueva foto en la base de datos y en Storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|max:10240', // Hasta 10MB
            'titulo_galeria' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'categoria_texto' => 'required|string|max:50',
            'categoria_badge' => 'required|string|in:primary,secondary,success,danger,warning,info,dark',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('galeria', 'public');

            Foto::create([
                'ruta' => 'storage/' . $path,
                'es_publica' => true,
                'titulo_galeria' => $request->titulo_galeria,
                'descripcion' => $request->descripcion,
                'categoria_texto' => $request->categoria_texto,
                'categoria_badge' => $request->categoria_badge,
                'encargo_id' => null, // Son fotos huérfanas dedicadas a la portada
            ]);

            return back()->with('success', '¡Foto añadida a la galería con éxito!');
        }

        return back()->withErrors('Error al subir la imagen.');
    }

    /**
     * Elimina una foto de la galería (mantiene los soft deletes si aplica).
     */
    public function destroy($id)
    {
        $foto = Foto::findOrFail($id);
        
        // Opcional: Borrar físicamente el archivo
        // Storage::disk('public')->delete(str_replace('storage/', '', $foto->ruta));

        $foto->delete();

        return back()->with('success', 'Foto retirada de la galería.');
    }
}
