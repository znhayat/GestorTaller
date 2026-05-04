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
    // Buscamos clientes por nombre o telf para el autocompletado del wizard
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

    // Aquí es donde se guarda todo: Cliente -> Coche -> Trabajo -> Presupuesto
    public function store(Request $request)
    {
        // Validamos que no falte nada importante
        $request->validate([
            'nombre'      => 'required|string|max:100',
            'apellido'    => 'required|string|max:100',
            'telefono'    => ['required', 'regex:/^[0-9]{9}$/'],
            'correo'      => 'required|email|max:150',
            'marca'       => 'required|string|max:50',
            'modelo'      => 'required|string|max:50',
            'descripcion' => 'required|string',
            'precio_materiales' => 'required|numeric|min:0',
            'precio_horas' => 'required|numeric|min:0',
            'cita_revision' => 'required|date|after_or_equal:today',
            'hora_cita' => 'required|date_format:H:i'
        ]);

        try {
            return DB::transaction(function () use ($request) {

                // Miramos si el cliente ya existe por el teléfono
                $cliente = Cliente::where('telefono', $request->telefono)->first();

                if (!$cliente) {
                    // Si es nuevo, lo creamos
                    $cliente = Cliente::create([
                        'nombre'   => $request->nombre,
                        'apellido' => $request->apellido,
                        'correo'   => $request->correo,
                        'telefono' => $request->telefono,
                    ]);
                } else {
                    // Si ya existe, actualizamos sus datos por si han cambiado
                    $cliente->update([
                        'nombre'   => $request->nombre,
                        'apellido' => $request->apellido,
                        'correo'   => $request->correo,
                    ]);
                }

                $marcaSlug = trim($request->marca);
                $modeloSlug = trim($request->modelo);

                // Guardamos la marca y el modelo en las tablas auxiliares si no están
                $marcaRef = \App\Models\Marca::firstOrCreate(['nombre' => $marcaSlug]);
                \App\Models\Modelo::firstOrCreate([
                    'marca_id' => $marcaRef->id,
                    'nombre'   => $modeloSlug
                ]);

                // Vinculamos el coche al cliente
                $vehiculo = Vehiculo::firstOrCreate([
                    'cliente_id' => $cliente->id,
                    'marca'      => $marcaSlug,
                    'modelo'     => $modeloSlug,
                ]);

                // Creamos el encargo y lo ponemos en "Cita Agendada"
                $encargo = Encargo::create([
                    'vehiculo_id'   => $vehiculo->id,
                    'descripcion'   => $request->descripcion,
                    'estado'        => 'Cita Agendada',
                    'fecha_entrada' => now(),
                    'cita_revision' => $request->cita_revision,
                    'hora_cita'     => $request->hora_cita,
                    'cita_recogida' => $request->cita_recogida,
                    'recordatorio_enviado' => false,
                ]);

                // Generamos el presupuesto inicial con lo que viene del wizard
                $total = $request->precio_materiales + $request->precio_horas;

                Presupuesto::create([
                    'encargo_id' => $encargo->id,
                    'estimacion_inicial' => $total,
                    'precio_materiales' => $request->precio_materiales,
                    'precio_horas' => $request->precio_horas,
                    'total' => $total,
                    'aceptado' => false
                ]);

                return redirect()->route('encargos.recepcion')
                    ->with('success', '¡Trabajo creado! Cita para el día ' . date('d/m/Y', strtotime($request->cita_revision)));
            });
        } catch (\Exception $e) {
            // Si algo peta, no guardamos nada y avisamos
            return back()->withErrors('Vaya, algo ha fallado: ' . $e->getMessage())->withInput();
        }
    }
}
