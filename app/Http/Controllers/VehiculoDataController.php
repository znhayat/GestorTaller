<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Marca;
use App\Models\Modelo;

class VehiculoDataController extends Controller
{
    public function buscarMarcas(Request $request)
    {
        $q = $request->query('q');
        $marcas = Marca::where('nombre', 'like', "%$q%")->limit(10)->get();
        return response()->json($marcas);
    }

    public function buscarModelos(Request $request)
    {
        $marcaId = $request->query('marca_id');
        $q = $request->query('q');
        
        $query = Modelo::query();
        if ($marcaId) {
            $query->where('marca_id', $marcaId);
        }
        if ($q) {
            $query->where('nombre', 'like', "%$q%");
        }
        
        $modelos = $query->limit(20)->get();
        return response()->json($modelos);
    }
}
