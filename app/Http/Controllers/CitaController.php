<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Encargo;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function index()
    {
        $citas = Cita::with('encargo.vehiculo.cliente')->orderBy('fecha')->orderBy('hora')->get();
        return view('content.citas.index', compact('citas'));
    }

    public function create()
    {
        // Necesitamos los encargos para saber a qué trabajo asignar la cita
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.citas.create', compact('encargos'));
    }

    public function store(Request $request)
    {
        Cita::create($request->all());
        return redirect()->route('citas.index')->with('success', 'Cita creada.');
    }

    public function edit($id)
    {
        $cita = Cita::findOrFail($id);
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.citas.edit', compact('cita', 'encargos'));
    }

    public function update(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);
        $cita->update($request->all());
        return redirect()->route('citas.index')->with('success', 'Cita actualizada.');
    }

    public function destroy($id)
    {
        Cita::findOrFail($id)->delete();
        return redirect()->route('citas.index')->with('success', 'Cita eliminada.');
    }
}
