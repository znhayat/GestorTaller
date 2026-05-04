<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    /**
     * Lista la base de datos completa de clientes.
     * Recupera todos los registros para mostrar en la tabla principal de administración.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $clientes = Cliente::when($search, function ($query, $search) {
            return $query->where('nombre', 'like', "%{$search}%")
                ->orWhere('apellido', 'like', "%{$search}%")
                ->orWhere('telefono', 'like', "%{$search}%")
                ->orWhere('correo', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('content.clientes.index', compact('clientes', 'search'));
    }

    /**
     * Carga el formulario de registro.
     * Proporciona la interfaz para dar de alta a nuevos clientes en el sistema.
     */
    public function create()
    {
        return view('content.clientes.create');
    }

    /**
     * Persiste el nuevo cliente en la base de datos.
     * Se utiliza 'only' para filtrar la entrada por seguridad, permitiendo 
     * solo los campos definidos en el esquema del taller.
     */
    public function store(Request $request)
    {
        Cliente::create($request->only(['nombre', 'apellido', 'telefono', 'correo']));

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    /**
     * Visualización detallada de un cliente.
     */
    public function show(string $id)
    {
        $cliente = Cliente::with(['vehiculos.encargos' => function($query) {
            $query->orderBy('created_at', 'desc');
        }, 'vehiculos.encargos.presupuesto', 'vehiculos.encargos.factura'])->findOrFail($id);
        
        return view('content.clientes.show', compact('cliente'));
    }

    /**
     * Obtiene los datos del cliente para modificarlos.
     * El método findOrFail garantiza que si el cliente no existe (por ID inválido), 
     * el sistema devuelva un error 404 controlado.
     */
    public function edit(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('content.clientes.edit', compact('cliente'));
    }

    /**
     * Actualiza la información del cliente.
     * Al igual que en el guardado, se restringe la entrada de datos mediante 'only'
     * para evitar la modificación de campos no autorizados.
     */
    public function update(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->only(['nombre', 'apellido', 'telefono', 'correo']));

        return redirect()->route('clientes.index')
            ->with('success', 'Datos del cliente actualizados.');
    }

    /**
     * Elimina el registro del cliente de forma permanente.
     */
    public function destroy(string $id)
    {
        Cliente::findOrFail($id)->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado del sistema.');
    }
}
