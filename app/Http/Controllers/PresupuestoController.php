<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use App\Models\Encargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresupuestoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $presupuestos = Presupuesto::with('encargo.vehiculo.cliente')
            ->when($search, function ($query, $search) {
                return $query->whereHas('encargo.vehiculo.cliente', function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido', 'like', "%{$search}%");
                });
            })
            ->latest()->paginate(15);

        return view('content.presupuestos.index', compact('presupuestos', 'search'));
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
            'precio_materiales' => 'required|numeric',
            'precio_horas' => 'required|numeric',
        ]);

        $total = $request->precio_materiales + $request->precio_horas;

        DB::transaction(function () use ($request, $total) {
            $presupuesto = Presupuesto::updateOrCreate(
                ['encargo_id' => $request->encargo_id],
                [
                    'precio_materiales' => $request->precio_materiales,
                    'precio_horas' => $request->precio_horas,
                    'total' => $total,
                    'aceptado' => false,
                ]
            );

            // Cambiar estado del encargo a 'Presupuesto Enviado'
            $encargo = Encargo::find($request->encargo_id);
            $encargo->estado = 'Presupuesto Enviado';
            $encargo->save();
        });

        return redirect()->route('encargos.recepcion')->with('success', 'Presupuesto guardado y enviado al cliente');
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
            $encargo->estado = 'Pendiente Inicio';
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

    public function quickUpdate(Request $request, $id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $totalNuevo = $request->total;
        
        $nota = "Actualizado tras revisión presencial. ";
        if ($presupuesto->estimacion_inicial) {
            $nota .= "Original telefónica: {$presupuesto->estimacion_inicial}€. ";
        }
        if ($request->has('nota_adicional')) {
            $nota .= $request->nota_adicional;
        }
        
        // Para simplificar el "a ojo", volcamos el total en materiales y 0 en horas si es actualización rápida
        $presupuesto->update([
            'precio_materiales' => $totalNuevo,
            'precio_horas' => 0,
            'total' => $totalNuevo,
            'notas' => $nota
        ]);
        
        return response()->json(['success' => true, 'message' => 'Presupuesto actualizado correctamente tras revisión.']);
    }
}
