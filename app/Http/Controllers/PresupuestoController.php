<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use App\Models\Encargo;
use Illuminate\Http\Request;

class PresupuestoController extends Controller
{
    // Listado de todos los presupuestos. 
    // Uso latest para que los últimos que se hagan salgan los primeros en la tabla.
    public function index()
    {
        $presupuestos = Presupuesto::with('encargo.vehiculo.cliente')->latest()->get();
        return view('content.presupuestos.index', compact('presupuestos'));
    }

    // Para crear uno nuevo, necesito la lista de encargos. 
    public function create()
    {
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.presupuestos.create', compact('encargos'));
    }

    // Guardamos el presupuesto. 
    // Aquí hago la suma de materiales + horas a mano para que el "total" se guarde ya calculado en la BD.
    public function store(Request $request)
    {
        $request->validate([
            'encargo_id' => 'required|exists:encargos,id',
            'precio_materiales' => 'required|numeric',
            'precio_horas' => 'required|numeric',
        ]);

        Presupuesto::create([
            'encargo_id' => $request->encargo_id,
            'precio_materiales' => $request->precio_materiales,
            'precio_horas' => $request->precio_horas,
            'total' => $request->precio_materiales + $request->precio_horas,
            'aceptado' => $request->has('aceptado') // Si el check está marcado, es true
        ]);

        return redirect()->route('presupuestos.index')->with('success', 'Presupuesto creado.');
    }

    // Buscamos el presupuesto para editarlo si nos hemos equivocado en algún precio.
    public function edit($id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $encargos = Encargo::all();
        return view('content.presupuestos.edit', compact('presupuesto', 'encargos'));
    }

    // Al actualizar, vuelvo a recalcular el total por si han cambiado las horas o los materiales.
    public function update(Request $request, $id)
    {
        $presupuesto = Presupuesto::findOrFail($id);

        $presupuesto->update([
            'precio_materiales' => $request->precio_materiales,
            'precio_horas' => $request->precio_horas,
            'total' => $request->precio_materiales + $request->precio_horas,
            'aceptado' => $request->has('aceptado')
        ]);

        return redirect()->route('presupuestos.index')->with('success', 'Presupuesto actualizado.');
    }

    // Borramos el presupuesto. 
    // Uso "back" para que te devuelva a la misma pantalla donde estabas.
    public function destroy($id)
    {
        Presupuesto::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Presupuesto eliminado.');
    }
}