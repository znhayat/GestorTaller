<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use App\Models\Encargo;
use Illuminate\Http\Request;

class PresupuestoController extends Controller
{
    public function index()
    {
        $presupuestos = Presupuesto::with('encargo.vehiculo.cliente')->latest()->get();
        return view('content.presupuestos.index', compact('presupuestos'));
    }

    public function create()
    {
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.presupuestos.create', compact('encargos'));
    }

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
            'aceptado' => $request->has('aceptado')
        ]);

        return redirect()->route('presupuestos.index')->with('success', 'Presupuesto creado.');
    }

    public function edit($id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $encargos = Encargo::all();
        return view('content.presupuestos.edit', compact('presupuesto', 'encargos'));
    }

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

    public function destroy($id)
    {
        Presupuesto::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Presupuesto eliminado.');
    }
}
