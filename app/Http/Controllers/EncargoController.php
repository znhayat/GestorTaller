<?php

namespace App\Http\Controllers;

use App\Models\Encargo;
use App\Models\Vehiculo;
use App\Models\Presupuesto;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EncargoController extends Controller
{
    /**
     * Lista de encargos (vista clásica)
     */
    public function index()
    {
        $encargos = Encargo::with('vehiculo.cliente', 'presupuesto')
            ->whereNotIn('estado', ['Cancelado', 'Entregado'])
            ->get();
        return view('content.encargos.index', compact('encargos'));
    }

    /**
     * Formulario para crear nuevo encargo
     */
    public function create()
    {
        $vehiculos = Vehiculo::with('cliente')->get();
        return view('content.encargos.create', compact('vehiculos'));
    }

    /**
     * Guardar nuevo encargo
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'descripcion' => 'required|string',
        ]);

        $encargo = Encargo::create([
            'vehiculo_id' => $request->vehiculo_id,
            'descripcion' => $request->descripcion,
            'estado' => 'Cita Agendada',
            'fecha_entrada' => now(),
        ]);

        return redirect()->route('encargos.index')
            ->with('success', 'Encargo creado correctamente');
    }

    /**
     * Mostrar un encargo específico
     */
    public function show($id)
    {
        $encargo = Encargo::with('vehiculo.cliente', 'presupuesto', 'factura')->findOrFail($id);
        return view('content.encargos.show', compact('encargo'));
    }

    /**
     * Formulario para editar un encargo
     */
    public function edit($id)
    {
        $encargo = Encargo::with(['vehiculo.cliente', 'presupuesto'])->findOrFail($id);
        $vehiculos = Vehiculo::with('cliente')->get();
        $materiales_lista = \App\Models\Material::all();

        return view('content.encargos.edit', compact('encargo', 'vehiculos', 'materiales_lista'));
    }

    /**
     * Actualizar un encargo
     */
    public function update(Request $request, $id)
    {
        $encargo = Encargo::findOrFail($id);

        $request->validate([
            'descripcion' => 'required|string',
            'estado' => 'string',
        ]);

        $encargo->update([
            'descripcion' => $request->descripcion,
            'estado' => $request->estado ?? $encargo->estado,
        ]);

        return redirect()->route('encargos.index')
            ->with('success', 'Encargo actualizado correctamente');
    }

    /**
     * Eliminar un encargo
     */
    public function destroy($id)
    {
        try {
            $encargo = Encargo::findOrFail($id);

            // Eliminar el presupuesto asociado primero
            if ($encargo->presupuesto) {
                $encargo->presupuesto->delete();
            }

            // Eliminar el encargo
            $encargo->delete();

            return redirect()->route('encargos.index')
                ->with('success', 'Encargo eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el encargo: ' . $e->getMessage());
        }
    }

    /**
     * TABLERO DE RECEPCIÓN
     */
    public function kanbanRecepcion()
    {
        $estados = [
            'Cita Agendada' => [
                'title' => 'Cita Agendada',
                'color' => 'primary',
                'description' => 'Cliente tiene cita para revisión',
                'bg' => 'bg-primary'
            ],
            'En Revision' => [
                'title' => 'En Revisión',
                'color' => 'info',
                'description' => 'Coche en taller, revisando',
                'bg' => 'bg-info'
            ],
            'Presupuesto Enviado' => [
                'title' => 'Presupuesto Enviado',
                'color' => 'warning',
                'description' => 'Esperando decisión del cliente',
                'bg' => 'bg-warning'
            ]
        ];

        $encargos = Encargo::with('vehiculo.cliente', 'presupuesto')
            ->whereIn('estado', ['Cita Agendada', 'En Revision', 'Presupuesto Enviado'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('content.taller.kanban-recepcion', compact('encargos', 'estados'));
    }

    /**
     * TABLERO DE PRODUCCIÓN
     */
    public function kanbanProduccion()
    {
        $estados = [
            'Pendiente Inicio' => [
                'title' => 'Pendiente Inicio',
                'color' => 'secondary',
                'description' => 'Presupuesto aceptado, por empezar',
                'bg' => 'bg-secondary'
            ],
            'En Produccion' => [
                'title' => 'En Producción',
                'color' => 'primary',
                'description' => 'Tapizado en proceso',
                'bg' => 'bg-primary'
            ],
            'Esperando Recogida' => [
                'title' => 'Esperando Recogida',
                'color' => 'info',
                'description' => 'Trabajo listo, esperando cliente',
                'bg' => 'bg-info'
            ]
        ];

        $encargos = Encargo::with('vehiculo.cliente', 'presupuesto', 'factura')
            ->whereIn('estado', ['Pendiente Inicio', 'En Produccion', 'Esperando Recogida'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('content.taller.kanban-produccion', compact('encargos', 'estados'));
    }

    /**
     * Cambiar estado con Drag & Drop
     */
    public function cambiarEstado(Request $request, $id)
    {
        $encargo = Encargo::findOrFail($id);
        $nuevoEstado = $request->estado;
        $estadoAnterior = $encargo->estado;

        $transiciones = [
            'Cita Agendada' => ['En Revision'],
            'En Revision' => ['Presupuesto Enviado'],
            'Presupuesto Enviado' => ['Pendiente Inicio', 'Cancelado'],
            'Pendiente Inicio' => ['En Produccion'],
            'En Produccion' => ['Esperando Recogida'],
            'Esperando Recogida' => ['Entregado'],
        ];

        if (!isset($transiciones[$estadoAnterior]) || !in_array($nuevoEstado, $transiciones[$estadoAnterior])) {
            return response()->json([
                'success' => false,
                'message' => "No se puede mover de '$estadoAnterior' a '$nuevoEstado'"
            ], 422);
        }

        $encargo->estado = $nuevoEstado;

        if ($nuevoEstado == 'Pendiente Inicio' && $encargo->presupuesto) {
            $encargo->presupuesto->update(['aceptado' => true]);
            $encargo->cita_recogida = now()->addDays(7);
        }

        if ($nuevoEstado == 'Cancelado') {
            if ($encargo->presupuesto) {
                $encargo->presupuesto->update(['aceptado' => false]);
            }
            $encargo->fecha_salida = now();
        }

        if ($nuevoEstado == 'Entregado') {
            if ($encargo->presupuesto && $encargo->presupuesto->aceptado) {
                Factura::firstOrCreate(
                    ['encargo_id' => $encargo->id],
                    [
                        'importe_total' => $encargo->presupuesto->total,
                        'pagado' => true,
                        'fecha_pago' => now(),
                        'cliente_id' => $encargo->vehiculo->cliente_id
                    ]
                );
                $encargo->fecha_salida = now();
            }
        }

        $encargo->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado',
            'nuevo_estado' => $nuevoEstado,
            'cancelado' => $nuevoEstado == 'Cancelado'
        ]);
    }
}
