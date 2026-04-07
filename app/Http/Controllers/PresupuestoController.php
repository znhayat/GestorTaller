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

    public function create(Request $request)
    {
        $encargos = Encargo::with('vehiculo.cliente')->get();
        $encargoSeleccionado = null;

        if ($request->has('encargo_id')) {
            $encargoSeleccionado = Encargo::with('vehiculo.cliente')->find($request->encargo_id);
        }

        return view('content.presupuestos.create', compact('encargos', 'encargoSeleccionado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'encargo_id' => 'required|exists:encargos,id',
            'precio_materiales' => 'required|numeric|min:0',
            'precio_horas' => 'required|numeric|min:0',
        ]);

        $aceptado = $request->has('aceptado');

        $presupuesto = Presupuesto::create([
            'encargo_id' => $request->encargo_id,
            'precio_materiales' => $request->precio_materiales,
            'precio_horas' => $request->precio_horas,
            'total' => $request->precio_materiales + $request->precio_horas,
            'aceptado' => $aceptado
        ]);

        // Cambiar el estado del encargo según si se acepta o no
        $encargo = Encargo::findOrFail($request->encargo_id);

        if ($aceptado) {
            // ACEPTADO: Pasa a PRODUCCIÓN con estado "Pendiente de Inicio"
            $encargo->estado = 'Pendiente de Inicio';
            $encargo->save();

            return redirect()->route('encargos.produccion')
                ->with('success', '¡Presupuesto ACEPTADO! El trabajo ha pasado al TABLERO DE PRODUCCIÓN.');
        } else {
            // NO ACEPTADO: Se queda en recepción
            $encargo->estado = 'Presupuesto Enviado';
            $encargo->save();

            return redirect()->route('encargos.recepcion')
                ->with('info', 'Presupuesto guardado. Esperando confirmación del cliente.');
        }
    }

    public function edit($id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.presupuestos.edit', compact('presupuesto', 'encargos'));
    }

    public function update(Request $request, $id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $aceptadoAnterior = $presupuesto->aceptado;
        $aceptadoNuevo = $request->has('aceptado');

        $presupuesto->update([
            'precio_materiales' => $request->precio_materiales,
            'precio_horas' => $request->precio_horas,
            'total' => $request->precio_materiales + $request->precio_horas,
            'aceptado' => $aceptadoNuevo
        ]);

        $encargo = $presupuesto->encargo;

        if ($aceptadoNuevo && !$aceptadoAnterior) {
            // CAMBIO A ACEPTADO: Pasa a producción
            $encargo->estado = 'Pendiente de Inicio';
            $encargo->save();

            return redirect()->route('encargos.produccion')
                ->with('success', '¡Presupuesto ACEPTADO! El trabajo ha pasado al TABLERO DE PRODUCCIÓN.');
        } elseif (!$aceptadoNuevo && $aceptadoAnterior) {
            $encargo->estado = 'Presupuesto Enviado';
            $encargo->save();

            return redirect()->route('encargos.recepcion')
                ->with('warning', 'Presupuesto marcado como no aceptado.');
        }

        return redirect()->route('presupuestos.index')
            ->with('success', 'Presupuesto actualizado correctamente.');
    }

    public function destroy($id)
    {
        Presupuesto::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Presupuesto eliminado.');
    }
}
