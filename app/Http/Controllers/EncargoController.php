<?php

namespace App\Http\Controllers;

use App\Models\Encargo;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class EncargoController extends Controller
{
    /**
     * Lista todas las órdenes de trabajo (encargos) activas.
     * Se cargan de forma ambiciosa (Eager Loading) las relaciones de vehículos y clientes
     * para minimizar las peticiones SQL al renderizar la tabla principal.
     */
    public function index()
    {
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.encargos.index', compact('encargos'));
    }
    public function kanban()
    {
        // Cargamos los encargos con sus clientes y vehículos para que no den error
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.taller.kanban', compact('encargos'));
    }

    public function cambiarEstado(Request $request, $id)
    {
        $encargo = Encargo::findOrFail($id);
        $encargo->estado = $request->estado;

        if ($request->estado == 'Finalizado') {
            $encargo->fecha_salida = now();
        }

        $encargo->save();
        return back()->with('success', 'Estado actualizado');
    }

    /**
     * Prepara el formulario de apertura de orden de trabajo.
     * Recupera los vehículos con sus respectivos dueños 
     */

    public function show($id)
    {
        // Carga el encargo, el cliente, el vehículo y los materiales relacionados
        $encargo = Encargo::with(['cliente', 'vehiculo', 'materiales'])->findOrFail($id);

        return view('encargos.show', compact('encargo'));
    }
    public function create()
    {
        $vehiculos = Vehiculo::with('cliente')->get();
        return view('content.encargos.create', compact('vehiculos'));
    }

    /**
     * Registra una nueva orden de trabajo en el sistema.
     */
    public function store(Request $request)
    {
        Encargo::create($request->all());
        return redirect()->route('encargos.index')
            ->with('success', 'Orden de trabajo abierta correctamente.');
    }

    /**
     * Carga la interfaz de gestión del encargo.
     * Este método unifica la información del vehículo, el cliente 
     * y materiales que se están consumiendo en la reparación.
     */
    public function edit($id)
    {
        // Se carga el encargo con el detalle de materiales para controlar el stock utilizado
        $encargo = Encargo::with(['vehiculo.cliente', 'usos_materiales.material'])->findOrFail($id);

        $vehiculos = Vehiculo::with('cliente')->get();
        $materiales_lista = \App\Models\Material::all();

        return view('content.encargos.edit', compact('encargo', 'vehiculos', 'materiales_lista'));
    }

    /**
     * Actualiza el estado del encargo o la información de la reparación.
     * Permite transicionar la orden entre estados (Pendiente, En Proceso, Finalizado).
     */
    public function update(Request $request, $id)
    {
        $encargo = Encargo::findOrFail($id);
        $encargo->update($request->all());

        return redirect()->route('encargos.index')
            ->with('success', 'Orden de trabajo actualizada.');
    }

    /**
     * Cierra y elimina el expediente del encargo.
     * Al estar vinculado por clave foránea, esta acción puede afectar a fotos 
     * y registros de materiales asociados según la política de borrado.
     */
    public function destroy($id)
    {
        Encargo::findOrFail($id)->delete();
        return redirect()->route('encargos.index')
            ->with('success', 'Orden de trabajo eliminada.');
    }
}
