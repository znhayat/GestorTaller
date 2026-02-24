<?php

namespace App\Http\Controllers;

use App\Models\Encargo;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class EncargoController extends Controller
{
    public function index()
    {
        // Cargamos el vehículo y su dueño para mostrarlo en la tabla
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.encargos.index', compact('encargos'));
    }

    public function create()
    {
        // Necesitamos los vehículos (y saber de quién son) para el desplegable
        $vehiculos = Vehiculo::with('cliente')->get();
        return view('content.encargos.create', compact('vehiculos'));
    }

    public function store(Request $request)
    {
        Encargo::create($request->all());
        return redirect()->route('encargos.index');
    }

    public function edit($id)
    {
        // Cargamos el encargo con sus materiales y los datos del vehículo
        $encargo = Encargo::with(['vehiculo.cliente', 'usos_materiales.material'])->findOrFail($id);
        $vehiculos = Vehiculo::with('cliente')->get();
        $materiales_lista = \App\Models\Material::all();

        return view('content.encargos.edit', compact('encargo', 'vehiculos', 'materiales_lista'));
    }

    public function update(Request $request, $id)
    {
        $encargo = Encargo::findOrFail($id);
        $encargo->update($request->all());
        return redirect()->route('encargos.index');
    }

    public function destroy($id)
    {
        Encargo::findOrFail($id)->delete();
        return redirect()->route('encargos.index');
    }
}
