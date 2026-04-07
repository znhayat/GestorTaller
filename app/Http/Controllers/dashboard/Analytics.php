<?php

namespace App\Http\Controllers\dashboard; // <--- Debe decir /dashboard

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Encargo;
use App\Models\Presupuesto;
use App\Models\Material;

class Analytics extends Controller
{
  public function index()
  {
    // Suma de todas las facturas
    $totalFacturado = Factura::sum('importe_total') ?? 0;

    $totalClientes = Cliente::count() ?? 0;
    $totalVehiculos = Vehiculo::count() ?? 0;

    // Presupuestos creados pero no aceptados
    $presupuestosPendientes = Presupuesto::where('aceptado', 0)->orWhereNull('aceptado')->count();

    // Encargos que no estén finalizados o entregados
    $encargosActivos = Encargo::whereNotIn('estado', ['Finalizado', 'Entregado', 'Cancelado'])->count();

    // Materiales en base de datos (por ahora total registrado)
    $totalMateriales = Material::count();

    return view('content.dashboard.dashboards-analytics', [
      'totalFacturado' => $totalFacturado,
      'totalClientes' => $totalClientes,
      'totalVehiculos' => $totalVehiculos,
      'presupuestosPendientes' => $presupuestosPendientes,
      'encargosActivos' => $encargosActivos,
      'totalMateriales' => $totalMateriales
    ]);
  }
}
