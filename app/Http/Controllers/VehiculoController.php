<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Cliente;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    // Sacamos la lista de todos los coches que tenemos registrados.
    // He usado el "with('cliente')" porque así Laravel trae el nombre del dueño 
    // de una sola vez y no va preguntando a la base de datos por cada coche.
    public function index(Request $request)
    {
        $search = $request->get('search');

        $vehiculos = Vehiculo::with('cliente')
            ->when($search, function ($query, $search) {
                return $query->where('marca', 'like', "%{$search}%")
                    ->orWhere('modelo', 'like', "%{$search}%")
                    ->orWhereHas('cliente', function ($q) use ($search) {
                        $q->where('nombre', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('content.vehiculos.index', compact('vehiculos', 'search'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        return view('content.vehiculos.create', compact('clientes'));
    }

    // Aquí guardamos los datos del vehículo (matrícula, marca, modelo...).
    public function store(Request $request)
    {
        Vehiculo::create($request->all());
        return redirect()->route('vehiculos.index');
    }

    public function edit($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $clientes = Cliente::all();
        return view('content.vehiculos.edit', compact('vehiculo', 'clientes'));
    }

    // Actualizamos la ficha del vehículo con los nuevos datos que vengan del formulario.
    public function update(Request $request, $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->update($request->all());
        return redirect()->route('vehiculos.index');
    }

    // Borramos el coche del sistema. 
    public function destroy($id)
    {
        Vehiculo::findOrFail($id)->delete();
        return redirect()->route('vehiculos.index');
    }
}
