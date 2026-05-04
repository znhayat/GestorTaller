<?php

namespace App\Http\Controllers;

use App\Models\Encargo;
use App\Models\Vehiculo;
use App\Models\Presupuesto;
use App\Models\Cita;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EncargoController extends Controller
{
    // Listado de trabajos activos (sin los terminados o cancelados)
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

    // Para ver qué presupuestos nos han rechazado
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

    // Historial de clientes (agrupamos para no repetir el mismo cliente mil veces)
    public function historial(Request $request)
    {
        $search = $request->get('search');

        $subQuery = Encargo::select(DB::raw('MAX(encargos.id) as id'))
            ->join('vehiculos', 'encargos.vehiculo_id', '=', 'vehiculos.id')
            ->groupBy('vehiculos.cliente_id');

        $encargos = Encargo::with('vehiculo.cliente', 'presupuesto', 'factura')
            ->whereIn('id', $subQuery)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('descripcion', 'like', "%{$search}%")
                        ->orWhere('estado', 'like', "%{$search}%")
                        ->orWhereHas('vehiculo.cliente', function ($q) use ($search) {
                            $q->where('nombre', 'like', "%{$search}%")
                                ->orWhere('apellido', 'like', "%{$search}%")
                                ->orWhere('telefono', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('content.encargos.historial', compact('encargos', 'search'));
    }

    public function create()
    {
        $vehiculos = Vehiculo::with('cliente')->get();
        return view('content.encargos.create', compact('vehiculos'));
    }

    // Guardar trabajo y subir fotos si hay
    public function store(Request $request)
    {
        $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'descripcion' => 'required|string',
            'fotos.*'     => 'image|max:3072'
        ]);

        $encargo = Encargo::create([
            'vehiculo_id' => $request->vehiculo_id,
            'descripcion' => $request->descripcion,
            'estado' => 'Cita Agendada',
            'fecha_entrada' => now(),
        ]);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $fotoFile) {
                $ruta = $fotoFile->store('trabajos', 'public');
                \App\Models\Foto::create([
                    'encargo_id' => $encargo->id,
                    'ruta' => $ruta,
                    'descripcion' => 'Foto entrada'
                ]);
            }
        }

        return redirect()->route('encargos.index')
            ->with('success', 'Trabajo creado correctamente.');
    }

    public function show($id)
    {
        $encargo = Encargo::with('vehiculo.cliente', 'presupuesto', 'factura')->findOrFail($id);
        return view('content.encargos.show', compact('encargo'));
    }

    public function edit($id)
    {
        $encargo = Encargo::with(['vehiculo.cliente', 'presupuesto'])->findOrFail($id);
        $vehiculos = Vehiculo::with('cliente')->get();
        $materiales_lista = \App\Models\Material::all();

        return view('content.encargos.edit', compact('encargo', 'vehiculos', 'materiales_lista'));
    }

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
            return redirect()->route('encargos.recepcion')->with('success', 'Guardado OK');
        } elseif ($origin == 'produccion') {
            return redirect()->route('encargos.produccion')->with('success', 'Guardado OK');
        }

        return redirect()->route('encargos.index')->with('success', 'Actualizado correctamente');
    }

    // Borrar trabajo (el modelo se encarga de la cascada para no romper nada)
    public function destroy($id)
    {
        try {
            $encargo = Encargo::findOrFail($id);
            $encargo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Trabajo eliminado'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Cuando el cliente acepta el presupuesto, agendamos el inicio real
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
                'message' => 'La fecha de entrega no tiene sentido.'
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
                'notas' => 'Cita de trabajo tras aceptar PPT'
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => '¡Presupuesto aceptado! Cita para el ' . date('d/m/Y', strtotime($request->fecha_inicio))
        ]);
    }

    // Tablero de Recepción
    public function kanbanRecepcion()
    {
        // Si hoy es el día de la cita, pasamos el coche a 'En Revisión' automáticamente
        Encargo::where('estado', 'Cita Agendada')
            ->whereDate('cita_revision', '<=', now()->toDateString())
            ->update(['estado' => 'En Revision']);

        $estados = [
            'Cita Agendada' => ['title' => 'Cita Agendada', 'color' => 'primary', 'bg' => 'bg-primary'],
            'En Revision' => ['title' => 'En Revisión', 'color' => 'info', 'bg' => 'bg-info'],
            'Presupuesto Enviado' => ['title' => 'Presupuesto Enviado', 'color' => 'warning', 'bg' => 'bg-warning']
        ];

        $encargos = Encargo::with('vehiculo.cliente', 'presupuesto')
            ->whereIn('estado', ['Cita Agendada', 'En Revision', 'Presupuesto Enviado'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('content.taller.kanban-recepcion', compact('encargos', 'estados'));
    }

    // Tablero de Producción
    public function kanbanProduccion()
    {
        $estados = [
            'Pendiente Inicio' => ['title' => 'Pendiente Inicio', 'color' => 'secondary', 'bg' => 'bg-secondary'],
            'En Produccion' => ['title' => 'En Producción', 'color' => 'primary', 'bg' => 'bg-primary'],
            'Esperando Recogida' => ['title' => 'Esperando Recogida', 'color' => 'info', 'bg' => 'bg-info']
        ];

        $encargos = Encargo::with('vehiculo.cliente', 'presupuesto', 'factura')
            ->whereIn('estado', ['Pendiente Inicio', 'En Produccion', 'Esperando Recogida'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('content.taller.kanban-produccion', compact('encargos', 'estados'));
    }

    // Mover tarjetas por el tablero
    public function cambiarEstado(Request $request, $id)
    {
        $encargo = Encargo::findOrFail($id);
        $nuevoEstado = $request->estado;
        $estadoAnterior = $encargo->estado;

        // Reglas de flujo para que no muevan cosas raras
        $transiciones = [
            'Cita Agendada' => ['En Revision'],
            'En Revision' => ['Presupuesto Enviado'],
            'Presupuesto Enviado' => ['Pendiente Inicio', 'Cancelado'],
            'Pendiente Inicio' => ['En Produccion'],
            'En Produccion' => ['Esperando Recogida'],
            'Esperando Recogida' => ['Entregado'],
        ];

        // Lo guardamos
        $encargo->estado = $nuevoEstado;

        if ($nuevoEstado == 'Pendiente Inicio' && $encargo->presupuesto) {
            $encargo->presupuesto->update(['aceptado' => true]);
        }

        if ($nuevoEstado == 'Cancelado') {
            if ($encargo->presupuesto) {
                $encargo->presupuesto->update(['aceptado' => false]);
            }
            $encargo->fecha_salida = now();
        }

        if ($nuevoEstado == 'Esperando Recogida' && $estadoAnterior == 'En Produccion') {
            // Avisamos internamente que ya se puede avisar al cliente
            Cita::create([
                'encargo_id' => $encargo->id,
                'tipo' => 'entrega',
                'fecha' => now()->toDateString(),
                'hora' => '17:00',
                'notas' => 'Avisar al cliente para recoger el coche'
            ]);
        }

        if ($nuevoEstado == 'Entregado') {
            if ($encargo->presupuesto && $encargo->presupuesto->aceptado) {
                Factura::firstOrCreate(
                    ['encargo_id' => $encargo->id],
                    [
                        'importe_total' => $encargo->presupuesto->total,
                        'pagado' => true,
                        'fecha_pago' => now()
                    ]
                );
                $encargo->fecha_salida = now();
            }
        }

        $encargo->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado cambiado',
            'nuevo_estado' => $nuevoEstado
        ]);
    }
}
