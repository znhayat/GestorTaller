<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Encargo;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    // Listado principal de facturas
    public function index()
    {
        $facturas = Factura::with('encargo.vehiculo.cliente')->latest()->get();
        return view('content.facturas.index', compact('facturas'));
    }

    // Formulario para crear factura
    public function create()
    {
        // Traemos los encargos para asociarlos a la factura
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.facturas.create', compact('encargos'));
    }

    // Guardar factura en la BD
    public function store(Request $request)
    {
        $request->validate([
            'encargo_id' => 'required|exists:encargos,id',
            'importe_total' => 'required|numeric|min:0',
            'fecha_pago' => 'nullable|date'
        ]);

        Factura::create([
            'encargo_id' => $request->encargo_id,
            'importe_total' => $request->importe_total,
            'pagado' => $request->has('pagado'), // true si el checkbox está marcado
            'fecha_pago' => $request->fecha_pago
        ]);

        return redirect()->route('facturas.index')->with('success', 'Factura generada con éxito.');
    }

    // Formulario de edición
    public function edit($id)
    {
        $factura = Factura::with('encargo.vehiculo.cliente')->findOrFail($id);
        $encargos = Encargo::all(); // Por si se desea reasignar el encargo
        return view('content.facturas.edit', compact('factura', 'encargos'));
    }

    // Actualizar datos
    public function update(Request $request, $id)
    {
        $request->validate([
            'importe_total' => 'required|numeric',
            'fecha_pago' => 'nullable|date'
        ]);

        $factura = Factura::findOrFail($id);
        $factura->update([
            'importe_total' => $request->importe_total,
            'pagado' => $request->has('pagado'),
            'fecha_pago' => $request->fecha_pago
        ]);

        return redirect()->route('facturas.index')->with('success', 'Factura actualizada.');
    }

    // Eliminar factura
    public function destroy($id)
    {
        Factura::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Factura eliminada.');
    }
}
