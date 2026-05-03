<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Encargo;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    // Listado principal con buscador por cliente o importe
    public function index(Request $request)
    {
        $search = $request->get('search');
        $facturas = Factura::with('encargo.vehiculo.cliente')
            ->when($search, function ($query, $search) {
                return $query->whereHas('encargo.vehiculo.cliente', function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('apellido', 'like', "%{$search}%");
                })->orWhere('importe_total', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);
            
        return view('content.facturas.index', compact('facturas', 'search'));
    }

    public function create()
    {
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.facturas.create', compact('encargos'));
    }

    // Guarda la nueva factura y marca si está pagada
    public function store(Request $request)
    {
        $request->validate([
            'encargo_id' => 'required|exists:encargos,id',
            'importe_total' => 'required|numeric|min:0'
        ]);

        Factura::create([
            'encargo_id' => $request->encargo_id,
            'importe_total' => $request->importe_total,
            'pagado' => $request->has('pagado'), 
            'fecha_pago' => $request->fecha_pago
        ]);

        return redirect()->route('facturas.index')->with('success', 'Factura creada correctamente.');
    }

    public function edit($id)
    {
        $factura = Factura::with('encargo.vehiculo.cliente')->findOrFail($id);
        $encargos = Encargo::all(); 
        return view('content.facturas.edit', compact('factura', 'encargos'));
    }

    // Actualiza los datos o el estado de pago
    public function update(Request $request, $id)
    {
        $factura = Factura::findOrFail($id);
        $factura->update([
            'importe_total' => $request->importe_total,
            'pagado' => $request->has('pagado'),
            'fecha_pago' => $request->fecha_pago
        ]);

        return redirect()->route('facturas.index')->with('success', 'Factura actualizada.');
    }

    public function destroy($id)
    {
        Factura::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Factura eliminada.');
    }

    // Genera la vista para imprimir el PDF profesional
    public function imprimir($id)
    {
        $factura = Factura::with([
            'encargo.vehiculo.cliente', 
            'encargo.usos_materiales.material', 
            'encargo.presupuesto'
        ])->findOrFail($id);

        return view('content.facturas.pdf', compact('factura'));
    }
}