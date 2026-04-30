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
        $categoriasExistentes = Foto::whereNotNull('categoria_texto')
            ->distinct()
            ->pluck('categoria_texto');
            
        return view('content.galeria.index', compact('fotos', 'categoriasExistentes'));
    }

    /**
     * Guarda una nueva foto (o par de fotos) en la base de datos y en Storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'titulo_galeria' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'categoria_texto' => 'required|string|max:50',
            'categoria_badge' => 'required|string|in:primary,secondary,success,danger,warning,info,dark',
        ];

        if ($request->has('es_antes_despues')) {
            $rules['foto_antes'] = 'required|image|max:10240';
            $rules['foto_despues'] = 'required|image|max:10240';
        } else {
            $rules['foto'] = 'required|image|max:10240';
        }

        $request->validate($rules);

        if ($request->has('es_antes_despues')) {
            // Lógica para par de fotos
            $pathAntes = $request->file('foto_antes')->store('galeria', 'public');
            $pathDespues = $request->file('foto_despues')->store('galeria', 'public');

            // Creamos la del "antes"
            $fotoAntes = Foto::create([
                'ruta' => $pathAntes,
                'tipo' => 'antes',
                'es_publica' => true,
                'titulo_galeria' => $request->titulo_galeria,
                'descripcion' => $request->descripcion,
                'categoria_texto' => $request->categoria_texto,
                'categoria_badge' => $request->categoria_badge,
            ]);

            // Creamos la del "después" vinculada a la anterior
            Foto::create([
                'ruta' => $pathDespues,
                'tipo' => 'despues',
                'relacion_id' => $fotoAntes->id,
                'es_publica' => true,
                'titulo_galeria' => $request->titulo_galeria,
                'descripcion' => $request->descripcion,
                'categoria_texto' => $request->categoria_texto,
                'categoria_badge' => $request->categoria_badge,
            ]);

            // Vinculamos la primera a la segunda también para navegación bidireccional si hiciera falta
            // Aunque con una dirección basta para la landing.
            
            return back()->with('success', '¡Par de fotos (Antes y Después) añadido con éxito!');
        }

        // Lógica normal
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('galeria', 'public');

            Foto::create([
                'ruta' => $path,
                'tipo' => 'normal',
                'es_publica' => true,
                'titulo_galeria' => $request->titulo_galeria,
                'descripcion' => $request->descripcion,
                'categoria_texto' => $request->categoria_texto,
                'categoria_badge' => $request->categoria_badge,
                'encargo_id' => null,
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
        
        // Si tiene una relación (es un par), buscamos la otra
        if ($foto->tipo === 'antes') {
            $otra = Foto::where('relacion_id', $foto->id)->first();
            if ($otra) $otra->delete();
        } elseif ($foto->tipo === 'despues' && $foto->relacion_id) {
            $otra = Foto::find($foto->relacion_id);
            if ($otra) $otra->delete();
        }

        $foto->delete();

        return back()->with('success', 'Foto (y su par si existía) retirada de la galería.');
    }
}
