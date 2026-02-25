<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Encargo;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    /**
     * Muestra el listado de la agenda.
     * Carga las relaciones en cascada (Encargo -> Vehículo -> Cliente) 
     * para evitar consultas excesivas a la base de datos.
     */
    public function index()
    {
        // Recuperamos citas ordenadas cronológicamente para la gestión diaria
        $citas = Cita::with('encargo.vehiculo.cliente')
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        return view('content.citas.index', compact('citas'));
    }

    /**
     * Muestra el formulario para programar una nueva cita.
     * Recuperamos los encargos activos para vincular la cita a un trabajo específico.
     */
    public function create()
    {
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.citas.create', compact('encargos'));
    }

    /**
     * Almacena la cita en la base de datos.
     * Utiliza asignación masiva. Es fundamental que el modelo Cita 
     * tenga definidos los campos $fillable.
     */
    public function store(Request $request)
    {
        Cita::create($request->all());
        
        return redirect()->route('citas.index')
            ->with('success', 'Cita programada correctamente en la agenda.');
    }

    /**
     * Recupera una cita específica para su edición.
     * Verifica la existencia mediante findOrFail para evitar errores de ejecución.
     */
    public function edit($id)
    {
        $cita = Cita::findOrFail($id);
        $encargos = Encargo::with('vehiculo.cliente')->get();
        
        return view('content.citas.edit', compact('cita', 'encargos'));
    }

    /**
     * Actualiza los datos de una cita existente (ej. cambio de fecha o descripción).
     */
    public function update(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);
        $cita->update($request->all());

        return redirect()->route('citas.index')
            ->with('success', 'La cita se ha actualizado correctamente.');
    }

    /**
     * Elimina el registro de la cita.
     * Ideal para cancelaciones o limpiezas de agenda.
     */
    public function destroy($id)
    {
        Cita::findOrFail($id)->delete();
        
        return redirect()->route('citas.index')
            ->with('success', 'Cita eliminada de la agenda.');
    }
}