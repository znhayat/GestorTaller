<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Encargo;
use App\Models\Presupuesto;
use Illuminate\Support\Facades\DB;

class AltaTrabajoController extends Controller
{
    /**
     * Búsqueda de clientes y sus vehículos para el autocompletado
     */
    public function buscarCliente(Request $request)
    {
        $term = $request->query('q');
        if (!$term) return response()->json([]);

        $clientes = Cliente::with('vehiculos')
            ->where('nombre', 'like', "%{$term}%")
            ->orWhere('apellido', 'like', "%{$term}%")
            ->orWhere('telefono', 'like', "%{$term}%")
            ->limit(5)
            ->get();

        return response()->json($clientes);
    }

    public function create()
    {
        return view('content.taller.nuevo-trabajo');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string',
            'apellido'    => 'required|string',
            'telefono'    => 'required|string',
            'correo'      => 'required|email',
            'marca'       => 'required|string',
            'modelo'      => 'required|string',
            'descripcion' => 'required|string',
            'precio_materiales' => 'required|numeric|min:0',
            'precio_horas' => 'required|numeric|min:0',
            'cita_revision' => 'required|date|after_or_equal:today', // Validación: fecha hoy o futura
            'hora_cita' => 'required|date_format:H:i',
            'cita_recogida' => 'nullable|date|after_or_equal:cita_revision' // Fecha de entrega
        ]);

        try {
            return DB::transaction(function () use ($request) {

                // 1. Obtener o crear cliente (Evitamos duplicados por teléfono)
                $cliente = Cliente::firstOrCreate(
                    ['telefono' => $request->telefono],
                    [
                        'nombre'   => $request->nombre,
                        'apellido' => $request->apellido,
                        'correo'   => $request->correo,
                    ]
                );

                // Si el cliente ya existía, actualizamos sus datos por si han cambiado
                if (!$cliente->wasRecentlyCreated) {
                    $cliente->update([
                        'nombre'   => $request->nombre,
                        'apellido' => $request->apellido,
                        'correo'   => $request->correo,
                    ]);
                }

                // 2. Obtener o crear vehículo para este cliente
                $vehiculo = Vehiculo::firstOrCreate(
                    [
                        'cliente_id' => $cliente->id,
                        'marca'      => $request->marca,
                        'modelo'     => $request->modelo,
                    ]
                );

                // 3. Crear encargo
                $encargo = Encargo::create([
                    'vehiculo_id'   => $vehiculo->id,
                    'descripcion'   => $request->descripcion,
                    'estado'        => 'Cita Agendada',
                    'fecha_entrada' => now(),
                    'cita_revision' => $request->cita_revision,
                    'hora_cita'     => $request->hora_cita,
                    'cita_recogida' => $request->cita_recogida, // Fecha límite o entrega
                    'recordatorio_enviado' => false,
                ]);

                // 4. Crear presupuesto inicial
                $total = $request->precio_materiales + $request->precio_horas;

                Presupuesto::create([
                    'encargo_id' => $encargo->id,
                    'precio_materiales' => $request->precio_materiales,
                    'precio_horas' => $request->precio_horas,
                    'total' => $total,
                    'aceptado' => false
                ]);

                return redirect()->route('encargos.recepcion')
                    ->with('success', 'Trabajo creado. Cita agendada para el ' . date('d/m/Y', strtotime($request->cita_revision)));
            });
        } catch (\Exception $e) {
            return back()->withErrors('Error al guardar: ' . $e->getMessage())->withInput();
        }
    }
}
