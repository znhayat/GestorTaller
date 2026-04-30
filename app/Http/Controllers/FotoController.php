<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Encargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FotoController extends Controller
{
    // Listado de fotos. He metido el "with" para que Laravel no haga mil consultas 
    // a la base de datos al querer sacar el nombre del cliente y el coche.
    public function index()
    {
        $fotos = Foto::with('encargo.vehiculo.cliente')->latest()->get();
        return view('content.fotos.index', compact('fotos'));
    }

    // Para el formulario de subida, necesito pasarle los encargos activos.
    // Así el usuario solo tiene que elegir el coche en el desplegable.
    public function create()
    {
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.fotos.create', compact('encargos'));
    }

    // Aquí guardamos la foto. Primero chequeamos que sea una imagen y que no pese
    // un mundo (máximo 2MB). Si el archivo llega bien, lo movemos a la carpeta 'trabajos'.
    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|max:10240',
            'encargo_id' => 'required|exists:encargos,id'
        ]);

        if ($request->hasFile('foto')) {
            // Guardamos el archivo físico en el storage público
            $ruta = $request->file('foto')->store('trabajos', 'public');

            // Metemos la ruta y el encargo en la base de datos
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

    // Si actualizamos la foto y subimos un archivo nuevo, borramos el antiguo del
    // disco.
    public function update(Request $request, $id)
    {
        $foto = Foto::findOrFail($id);

        if ($request->hasFile('foto')) {
            $request->validate(['foto' => 'image|max:10240']);
            Storage::disk('public')->delete($foto->ruta);
            $foto->ruta = $request->file('foto')->store('trabajos', 'public');
        }

        $foto->encargo_id = $request->encargo_id;
        $foto->descripcion = $request->descripcion;
        $foto->save();

        return redirect()->route('fotos.index')->with('success', 'Registro actualizado.');
    }

    // Para borrar la foto, primero la quitamos del disco y luego de la base de datos.
    // Es clave hacerlo en este orden para que no se nos quede el archivo colgado.
    public function destroy($id)
    {
        $foto = Foto::findOrFail($id);
        
        // Primero borramos el archivo real del servidor
        Storage::disk('public')->delete($foto->ruta);
        
        // Y luego borramos la fila en la BD
        $foto->delete();

        return redirect()->back()->with('success', 'Foto eliminada.');
    }
}