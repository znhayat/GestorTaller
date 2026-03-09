<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Encargo;
use Illuminate\Support\Facades\DB;

class AltaTrabajoController extends Controller
{
    public function create()
    {
        return view('content.taller.nuevo-trabajo');
    }

    public function store(Request $request)
    {
        // 1. Validación estricta
        $validated = $request->validate([
            'nombre'      => 'required|string',
            'apellido'    => 'required|string',
            'telefono'    => 'required|string',
            'correo'      => 'required|email',
            'matricula'   => 'required|string',
            'modelo'      => 'required|string',
            'descripcion' => 'required|string',
        ]);

        try {
            // 2. Usamos una transacción para asegurar integridad
            DB::transaction(function () use ($request) {
                // Crear o encontrar cliente
                $cliente = Cliente::firstOrCreate(
                    ['telefono' => $request->telefono],
                    [
                        'nombre'   => $request->nombre,
                        'apellido' => $request->apellido,
                        'correo'   => $request->correo
                    ]
                );

                // Crear vehículo
                $vehiculo = $cliente->vehiculos()->create([
                    'matricula' => $request->matricula,
                    'modelo'    => $request->modelo
                ]);

                // Crear el encargo vinculado al vehículo
                Encargo::create([
                    'cliente_id'  => $cliente->id,
                    'vehiculo_id' => $vehiculo->id,
                    'descripcion' => $request->descripcion
                ]);
            });

            return redirect()->route('trabajo.index')->with('success', 'Trabajo registrado con éxito.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()]);
        }
    }
}
