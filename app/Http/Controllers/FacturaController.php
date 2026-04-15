<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Encargo;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    /**
     * Lista el historial económico del taller.
     * Recupera las facturas junto con la relación del vehículo y el titular
     * para facilitar la identificación rápida de los cobros realizados o pendientes.
     */
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
            ->latest()->paginate(15);
            
        return view('content.facturas.index', compact('facturas', 'search'));
    }

    public function create()
    {
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.facturas.create', compact('encargos'));
    }

    /**
     * Registra la factura en el sistema.
     * Procesa el estado de pago mediante la verificación del checkbox 'pagado'.
     */
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
            'pagado' => $request->has('pagado'), 
            'fecha_pago' => $request->fecha_pago
        ]);

        return redirect()->route('facturas.index')->with('success', 'Factura generada con éxito.');
    }

    /**
     * Incluye la carga de datos del cliente para proporcionar contexto al administrador.
     */
    public function edit($id)
    {
        $factura = Factura::with('encargo.vehiculo.cliente')->findOrFail($id);
        $encargos = Encargo::all(); 
        
        return view('content.facturas.edit', compact('factura', 'encargos'));
    }

    /**
     * Actualiza la información financiera o el estado de cobro.
     * Permite registrar pagos recibidos a posteriori de la emisión de la factura.
     */
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

    /**
     * Elimina el registro contable de la factura.
     * Se utiliza redirect()->back() para mantener al usuario en su flujo de trabajo actual.
     */
    public function destroy($id)
    {
        Factura::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Factura eliminada.');
    }
}