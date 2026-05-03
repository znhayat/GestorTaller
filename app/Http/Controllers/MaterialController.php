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
        $tipo = $request->get('tipo');

        // Si hay una búsqueda o se ha seleccionado un tipo, mostramos la tabla
        if ($search || $tipo) {
            $materiales = Material::when($search, function ($query, $search) {
                return $query->where('nombre', 'like', "%{$search}%")
                    ->orWhere('tipo', 'like', "%{$search}%");
            })
            ->when($tipo, function ($query, $tipo) {
                return $query->where('tipo', $tipo);
            })
            ->orderBy('nombre')
            ->paginate(20);

            return view('content.materiales.index', compact('materiales', 'search', 'tipo'));
        }

        // Si no hay nada seleccionado, mostramos la rejilla de categorías
        $categorias = Material::select('tipo', \DB::raw('count(*) as total'))
            ->groupBy('tipo')
            ->get();

        return view('content.materiales.categorias', compact('categorias'));
    }

    public function create()
    {
        // Definimos las categorías oficiales del taller
        $categorias = [
            'Tejidos y pieles',
            'Espumas y rellenos',
            'Hilos y sistemas de costura',
            'Elementos metálicos y fijaciones',
            'Adhesivos y selladores',
            'Preparación de superficies'
        ];
        
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
        $categorias = [
            'Tejidos y pieles',
            'Espumas y rellenos',
            'Hilos y sistemas de costura',
            'Elementos metálicos y fijaciones',
            'Adhesivos y selladores',
            'Preparación de superficies'
        ];
        return view('content.materiales.edit', compact('material', 'categorias'));
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

    /**
     * Buscador de materiales para el autocompletado (API interna)
     */
    public function buscar(Request $request)
    {
        $term = $request->query('q');
        if (!$term) return response()->json([]);

        $materiales = Material::where('nombre', 'like', "%{$term}%")
            ->orWhere('tipo', 'like', "%{$term}%")
            ->limit(10)
            ->get();

        return response()->json($materiales);
    }
}
