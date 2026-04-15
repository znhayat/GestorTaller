<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    // Vista principal: si no hay $tipo, muestra los bloques. Si hay $tipo, muestra la tabla.
    public function index(Request $request)
    {
        $search = $request->get('search');

        $materiales = Material::when($search, function ($query, $search) {
            return $query->where('nombre', 'like', "%{$search}%")
                ->orWhere('categoria', 'like', "%{$search}%")
                ->orWhere('unidad', 'like', "%{$search}%");
        })
            ->orderBy('nombre')
            ->paginate(15);

        return view('content.materiales.index', compact('materiales', 'search'));
    }

    public function create()
    {
        // Pasamos las categorías existentes para el select
        $categorias = Material::distinct()->pluck('tipo');
        return view('content.materiales.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        Material::create($request->all());
        return redirect()->route('materiales.index');
    }

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

    public function destroy($id)
    {
        Material::findOrFail($id)->delete();
        return redirect()->route('materiales.index');
    }
}
