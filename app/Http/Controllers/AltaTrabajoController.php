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
        //Validación de los campos que vienen del formulario
        $request->validate([
            'nombre'      => 'required|string',
            'apellido'    => 'required|string',
            'telefono'    => 'required|string',
            'correo'      => 'required|email',
            'marca'       => 'required|string',
            'modelo'      => 'required|string',
            'descripcion' => 'required|string',
        ]);

        try {
            return DB::transaction(function () use ($request) {

                $cliente = Cliente::firstOrCreate(
                    ['telefono' => $request->telefono],
                    [
                        'nombre'   => $request->nombre,
                        'apellido' => $request->apellido,
                        'correo'   => $request->correo,
                    ]
                );

                // El Vehículo
                $vehiculo = Vehiculo::create([
                    'cliente_id' => $cliente->id,
                    'marca'      => $request->marca,
                    'modelo'     => $request->modelo,
                ]);

                // El Encargo (Detalles y fechas)
                Encargo::create([
                    'vehiculo_id'   => $vehiculo->id,
                    'descripcion'   => $request->descripcion,
                    'estado'        => 'Pendiente',
                    'fecha_entrada' => now(),
                    'fecha_salida'  => null,
                ]);

                return redirect()->route('encargos.index')->with('success', '¡Trabajo registrado correctamente!');
            });
        } catch (\Exception $e) {
            return back()->withErrors('Error al guardar: ' . $e->getMessage())->withInput();
        }
    }
}
