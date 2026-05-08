<?php

namespace App\Http\Controllers;

use App\Models\Encargo;
use App\Models\Vehiculo;
use App\Models\Presupuesto;
use App\Models\Cita;
use App\Models\Factura;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EncargoController extends Controller
{
    /**
     * Lista de encargos (vista clásica)
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $encargos = Encargo::with('vehiculo.cliente', 'presupuesto')
            ->whereNotIn('estado', ['Cancelado', 'Entregado'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('descripcion', 'like', "%{$search}%")
                        ->orWhere('estado', 'like', "%{$search}%")
                        ->orWhereHas('vehiculo', function ($q) use ($search) {
                            $q->where('marca', 'like', "%{$search}%")
                                ->orWhere('modelo', 'like', "%{$search}%");
                        })
                        ->orWhereHas('vehiculo.cliente', function ($q) use ($search) {
                            $q->where('nombre', 'like', "%{$search}%")
                                ->orWhere('apellido', 'like', "%{$search}%")
                                ->orWhere('telefono', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('content.encargos.index', compact('encargos', 'search'));
    }

    /**
     * Lista de presupuestos rechazados o encargos cancelados.
     */
    public function rechazados(Request $request)
    {
        $search = $request->get('search');

        $encargos = Encargo::with('vehiculo.cliente', 'presupuesto')
            ->where('estado', 'Cancelado')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('descripcion', 'like', "%{$search}%")
                        ->orWhereHas('vehiculo', function ($q) use ($search) {
                            $q->where('marca', 'like', "%{$search}%")
                                ->orWhere('modelo', 'like', "%{$search}%");
                        })
                        ->orWhereHas('vehiculo.cliente', function ($q) use ($search) {
                            $q->where('nombre', 'like', "%{$search}%")
                                ->orWhere('apellido', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('content.encargos.rechazados', compact('encargos', 'search'));
    }

    /**
     * Historial completo de todos los trabajos entregados
     */
    public function historial(Request $request)
    {
        $search = $request->get('search');

        // Buscamos clientes únicos que tengan encargos finalizados o cancelados
        $clientes = Cliente::with(['vehiculos.encargos' => function($q) {
                $q->whereIn('estado', ['Entregado', 'Cancelado'])->orderBy('updated_at', 'desc');
            }])
            ->whereHas('vehiculos.encargos', function($q) {
                $q->whereIn('estado', ['Entregado', 'Cancelado']);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido', 'like', "%{$search}%")
                        ->orWhere('telefono', 'like', "%{$search}%")
                        ->orWhereHas('vehiculos', function ($q) use ($search) {
                            $q->where('marca', 'like', "%{$search}%")
                                ->orWhere('modelo', 'like', "%{$search}%")
                                ->orWhere('matricula', 'like', "%{$search}%");
                        });
                });
            })
            ->paginate(15);

        return view('content.encargos.historial', compact('clientes', 'search'));
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
            'fotos.*'     => 'image|max:3072' // validar cadascuna de les fotos
        ]);

        $encargo = Encargo::create([
            'vehiculo_id' => $request->vehiculo_id,
            'descripcion' => $request->descripcion,
            'estado' => 'Cita Agendada',
            'fecha_entrada' => now(),
        ]);

        // Guardar fotos vinculades a l'encàrrec si n'hi ha
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $fotoFile) {
                // S'empra explícitament el controller manualment o la lògica equivalent
                $ruta = $fotoFile->store('trabajos', 'public');
                \App\Models\Foto::create([
                    'encargo_id' => $encargo->id,
                    'ruta' => $ruta,
                    'descripcion' => 'Imatge de Recepció'
                ]);
            }
        }

        return redirect()->route('encargos.index')
            ->with('success', 'Encargo creado y preparado en la cola.');
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

        $origin = $request->input('origin');
        if ($origin == 'recepcion') {
            return redirect()->route('encargos.recepcion')->with('success', 'Encargo actualizado correctamente');
        } elseif ($origin == 'produccion') {
            return redirect()->route('encargos.produccion')->with('success', 'Encargo actualizado correctamente');
        }

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

            // Borrado lógico. La cascada segura está programada en el modelo Encargo
            // protegiendo y omitiendo a las facturas para que la contabilidad global siga OK.
            $encargo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Trabajo eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    public function aceptarYProgramar(Request $request, $id)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'hora_inicio' => 'required',
            'fecha_recogida' => 'required|date',
        ]);

        if ($request->fecha_recogida < $request->fecha_inicio) {
            return response()->json([
                'success' => false,
                'message' => 'La data de lliurament no pot ser anterior a la data d\'entrada.'
            ], 422);
        }

        $encargo = Encargo::findOrFail($id);

        DB::transaction(function () use ($request, $encargo) {
            $encargo->estado = 'Pendiente Inicio';
            $encargo->fecha_inicio_trabajo = $request->fecha_inicio;
            $encargo->hora_inicio_trabajo = $request->hora_inicio;
            $encargo->cita_recogida = $request->fecha_recogida;

            if ($encargo->presupuesto) {
                $encargo->presupuesto->aceptado = true;
                $encargo->presupuesto->save();
            }

            $encargo->save();

            Cita::create([
                'encargo_id' => $encargo->id,
                'tipo' => 'trabajo',
                'fecha' => $request->fecha_inicio,
                'hora' => $request->hora_inicio,
                'notas' => 'Trabajo programado tras aceptacion del presupuesto'
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Presupuesto aceptado. Cita de trabajo programada para el ' . date('d/m/Y', strtotime($request->fecha_inicio)) . ' a las ' . $request->hora_inicio . ' horas.'
        ]);
    }

    /**
     * TABLERO DE RECEPCIÓN
     */
    public function kanbanRecepcion()
    {
        // -------------------------------------------------------------------------
        // LÓGICA AUTOMÁTICA DE CITAS (Actualización Diaria)
        // Movemos automáticamente todos los encargos con 'Cita Agendada' al estado
        // 'En Revisión' si la fecha del sistema ya alcanzó o superó el día de la cita.
        // -------------------------------------------------------------------------
        Encargo::where('estado', 'Cita Agendada')
            ->whereDate('cita_revision', '<=', now()->toDateString())
            ->update(['estado' => 'En Revision']);

        // Definición formal de los estados correspondientes a este tablero
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

        if ($estadoAnterior == 'Cancelado' && $nuevoEstado != 'En Revision') {
            return response()->json([
                'success' => false,
                'message' => "Un trabajo Cancelado solo puede reactivarse devolviéndolo a 'En Revisión'."
            ], 422);
        }

        if ($estadoAnterior != 'Cancelado' && (!isset($transiciones[$estadoAnterior]) || !in_array($nuevoEstado, $transiciones[$estadoAnterior]))) {
            return response()->json([
                'success' => false,
                'message' => "No se puede mover de '$estadoAnterior' a '$nuevoEstado'"
            ], 422);
        }

        // Regla de negocio: sin presupuesto no se puede pasar a producción
        if ($nuevoEstado == 'Pendiente Inicio' && !$encargo->presupuesto) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede iniciar el trabajo sin un presupuesto aprobado. Crea primero el presupuesto.'
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

        if ($nuevoEstado == 'Esperando Recogida' && $estadoAnterior == 'En Produccion') {
            // "Alerta Interna": Automàticament registrem l'esdeveniment i programem Cita Lliurament fictícia inicial
            // Per emular notificació. Funcionalitat Pro petita pero superútil.
            Cita::create([
                'encargo_id' => $encargo->id,
                'tipo' => 'entrega',
                'fecha' => now()->toDateString(),
                'hora' => '17:00', // hora referencial
                'notas' => '*AVISO AUTOMÁTICO*: Ya se puede avisar al cliente para la recogida.'
            ]);
            $encargo->notas_internas .= "\n[AVISO] Trabalho Finalizado: Contactar cliente.";
        }

        if ($nuevoEstado == 'Entregado') {
            if ($encargo->presupuesto && $encargo->presupuesto->aceptado) {
                Factura::firstOrCreate(
                    ['encargo_id' => $encargo->id],
                    [
                        'importe_total' => $encargo->presupuesto->total,
                        'pagado' => false, // Cambiado a false para permitir el marcado manual según el manual de usuario
                        'fecha_pago' => null
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
