<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    // Listado de todo lo que tenemos en el almacén
    public function index()
    {
        $materiales = Material::all();
        return view('content.materiales.index', compact('materiales'));
    }

    // Solo nos lleva a la vista para dar de alta un producto nuevo
    public function create()
    {
        return view('content.materiales.create');
    }

    // Aquí es donde guardamos el material nuevo. 
    // Usamos el 'all' para pillar todos los campos del formulario (nombre, stock, precio...)
    public function store(Request $request)
    {
        Material::create($request->all());
        return redirect()->route('materiales.index');
    }

    // Buscamos un material específico para ver qué tenemos que cambiar.
    public function edit($id)
    {
        $material = Material::findOrFail($id);
        return view('content.materiales.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        $material->update($request->all());
        return redirect()->route('materiales.index');
    }

    // Borramos el material de la lista. 
    public function destroy($id)
    {
        Material::findOrFail($id)->delete();
        return redirect()->route('materiales.index');
    }
}
