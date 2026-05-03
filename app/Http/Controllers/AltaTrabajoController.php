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
            'nombre'      => 'required|string|max:100',
            'apellido'    => 'required|string|max:100',
            'telefono'    => ['required', 'regex:/^[0-9]{9}$/'], // Exactamente 9 números
            'correo'      => 'required|email|max:150',
            'marca'       => 'required|string|max:50',
            'modelo'      => 'required|string|max:50',
            'descripcion' => 'required|string',
            'precio_materiales' => 'required|numeric|min:0',
            'precio_horas' => 'required|numeric|min:0',
            'cita_revision' => 'required|date|after_or_equal:today',
            'hora_cita' => 'required|date_format:H:i',
            'cita_recogida' => 'nullable|date|after_or_equal:cita_revision'
        ], [
            'nombre.required' => 'El nombre del cliente es obligatorio.',
            'telefono.required' => 'El número de teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono debe tener 9 dígitos numéricos.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El formato del correo electrónico no es válido.',
            'cita_revision.after_or_equal' => 'La fecha de la cita no puede ser anterior a hoy.',
            'cita_recogida.after_or_equal' => 'La fecha de entrega no puede ser anterior a la de revisión.',
            'precio_materiales.numeric' => 'El precio debe ser un número.',
            'hora_cita.date_format' => 'El formato de hora debe ser HH:MM.'
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
                    'estimacion_inicial' => $total, // Guardamos la oferta telefónica original
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
