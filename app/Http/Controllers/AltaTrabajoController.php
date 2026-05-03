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
     * Búsqueda de clientes y sus vehículos para el autocompletado en el formulario.
     * Permite agilizar la carga de datos si el cliente ya ha venido al taller.
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

    /**
     * Proceso principal de Alta de Trabajo.
     * Gestiona en una sola transacción: Cliente -> Vehículo -> Encargo -> Presupuesto.
     */
    public function store(Request $request)
    {
        // 1. Validación estricta de los datos recibidos
        $request->validate([
            'nombre'      => 'required|string|max:100',
            'apellido'    => 'required|string|max:100',
            'telefono'    => ['required', 'regex:/^[0-9]{9}$/'], // Teléfono de 9 dígitos
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

                /**
                 * GESTIÓN DEL CLIENTE
                 * Usamos el teléfono como identificador único para evitar duplicar fichas.
                 * Si el cliente ya existe, lo recuperamos; si no, lo creamos.
                 */
                $cliente = Cliente::where('telefono', $request->telefono)->first();

                if (!$cliente) {
                    // Si no existe el teléfono, creamos ficha nueva desde cero
                    $cliente = Cliente::create([
                        'nombre'   => $request->nombre,
                        'apellido' => $request->apellido,
                        'correo'   => $request->correo,
                        'telefono' => $request->telefono,
                    ]);
                } else {
                    /** 
                     * IMPORTANTE: Si el cliente ya existe por teléfono, actualizamos sus datos 
                     * solo si el usuario ha escrito algo diferente. Esto asegura que la base de 
                     * datos esté siempre al día con el último contacto/nombre facilitado.
                     */
                    $cliente->update([
                        'nombre'   => $request->nombre,
                        'apellido' => $request->apellido,
                        'correo'   => $request->correo,
                    ]);
                }

                /**
                 * GESTIÓN DEL VEHÍCULO
                 * Vinculamos el coche al cliente. Si es un coche nuevo para este cliente, se crea.
                 */
                $marcaSlug = trim($request->marca);
                $modeloSlug = trim($request->modelo);

                // Aseguramos que la Marca y el Modelo existan en nuestro catálogo auxiliar
                $marcaRef = \App\Models\Marca::firstOrCreate(['nombre' => $marcaSlug]);
                \App\Models\Modelo::firstOrCreate([
                    'marca_id' => $marcaRef->id,
                    'nombre'   => $modeloSlug
                ]);

                // Buscamos si el cliente ya tiene este coche registrado (evita duplicar matrículas/modelos)
                $vehiculo = Vehiculo::firstOrCreate([
                    'cliente_id' => $cliente->id,
                    'marca'      => $marcaSlug,
                    'modelo'     => $modeloSlug,
                ]);

                /**
                 * CREACIÓN DEL ENCARGO (TRABAJO)
                 * Se registra la entrada al taller y se agenda la cita inicial.
                 */
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

                /**
                 * PRESUPUESTO ESTIMADO
                 * Generamos un presupuesto base basado en los servicios seleccionados en el configurador.
                 */
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
                    ->with('success', '¡Trabajo creado con éxito! Cita agendada para el ' . date('d/m/Y', strtotime($request->cita_revision)));
            });
        } catch (\Exception $e) {
            // Si algo falla, volvemos atrás sin guardar nada (integridad de datos)
            return back()->withErrors('Error al procesar el alta: ' . $e->getMessage())->withInput();
        }
    }
}
