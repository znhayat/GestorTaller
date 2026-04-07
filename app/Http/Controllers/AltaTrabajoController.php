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
    public function create()
    {
        return view('content.taller.nuevo-trabajo');
    }

    public function store(Request $request)
    {
        // Validación de campos
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
        ]);

        try {
            return DB::transaction(function () use ($request) {

                // 1️⃣ Crear o encontrar cliente
                $cliente = Cliente::firstOrCreate(
                    ['telefono' => $request->telefono],
                    [
                        'nombre'   => $request->nombre,
                        'apellido' => $request->apellido,
                        'correo'   => $request->correo,
                    ]
                );

                // 2️⃣ Crear vehículo
                $vehiculo = Vehiculo::create([
                    'cliente_id' => $cliente->id,
                    'marca'      => $request->marca,
                    'modelo'     => $request->modelo,
                ]);

                // 3️⃣ Crear encargo (estado inicial: Cita Agendada)
                $encargo = Encargo::create([
                    'vehiculo_id'   => $vehiculo->id,
                    'descripcion'   => $request->descripcion,
                    'estado'        => 'Cita Agendada',
                    'fecha_entrada' => now(),
                ]);

                // 4️⃣ Crear presupuesto inicial (NO aceptado)
                $total = $request->precio_materiales + $request->precio_horas;

                Presupuesto::create([
                    'encargo_id' => $encargo->id,
                    'precio_materiales' => $request->precio_materiales,
                    'precio_horas' => $request->precio_horas,
                    'total' => $total,
                    'aceptado' => false
                ]);

                // 5️⃣ Redirigir al Kanban de Recepción con mensaje de éxito
                return redirect()->route('encargos.recepcion')
                    ->with('success', '✅ ¡Trabajo creado correctamente! Aparecerá en la columna "Cita Agendada".');
            });
        } catch (\Exception $e) {
            return back()->withErrors('❌ Error al guardar: ' . $e->getMessage())->withInput();
        }
    }
}
