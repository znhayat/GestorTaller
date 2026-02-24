<?php

namespace App\Http\Controllers;

use App\Models\UsoMaterial;
use App\Models\Material;
use App\Models\Encargo;
use Illuminate\Http\Request;

class UsoMaterialController extends Controller
{
    // Aquí saco el historial de qué materiales se han ido usando.
    public function index()
    {
        $usos = UsoMaterial::with(['encargo.vehiculo', 'material'])->latest()->get();
        return view('content.usos_materiales.index', compact('usos'));
    }

    // Para registrar un uso, paso la lista de encargos abiertos y los materiales
    // que tenemos en stock. 
    public function create()
    {
        $encargos = Encargo::with('vehiculo.cliente')->get();
        $materiales = Material::all();
        return view('content.usos_materiales.create', compact('encargos', 'materiales'));
    }

    // Cuando guardas, busco el precio que tiene el material en ese momento y
    // lo multiplico por la cantidad que se diga. Así calculo el coste total automáticamente.
    public function store(Request $request)
    {
        $material = Material::findOrFail($request->material_id);
        $costo_total = $material->precio_unitario * $request->cantidad;

        UsoMaterial::create([
            'encargo_id' => $request->encargo_id,
            'material_id' => $request->material_id,
            'cantidad' => $request->cantidad,
            'costo_total' => $costo_total
        ]);
        return redirect()->route('usos_materiales.index')->with('success', 'Registro creado.');
    }

    // Si te has equivocado al poner la cantidad o la pieza, aquí busco el registro
    // y te vuelvo a cargar los desplegables por si necesitas cambiar de material.
    public function edit($id)
    {
        $uso = UsoMaterial::findOrFail($id);
        $encargos = Encargo::with('vehiculo.cliente')->get();
        $materiales = Material::all();
        return view('content.usos_materiales.edit', compact('uso', 'encargos', 'materiales'));
    }

    // Al actualizar, vuelvo a hacer el cálculo del coste total. 
    public function update(Request $request, $id)
    {
        $uso = UsoMaterial::findOrFail($id);
        $material = Material::findOrFail($request->material_id);
        $costo_total = $material->precio_unitario * $request->cantidad;

        $uso->update([
            'encargo_id' => $request->encargo_id,
            'material_id' => $request->material_id,
            'cantidad' => $request->cantidad,
            'costo_total' => $costo_total
        ]);
        return redirect()->route('usos_materiales.index')->with('success', 'Registro actualizado.');
    }

    // Si borras un registro, simplemente desaparece del historial del encargo.
    public function destroy($id)
    {
        UsoMaterial::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Eliminado correctamente.');
    }
}
