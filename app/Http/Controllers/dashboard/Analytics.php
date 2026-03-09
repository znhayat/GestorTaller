<?php

namespace App\Http\Controllers\dashboard; // <--- Debe decir /dashboard

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\Cliente;
use App\Models\Vehiculo;

class Analytics extends Controller
{
  public function index()
  {
    // $totalFacturado = Factura::sum('total') ?? 0;
    $totalFacturado = 0;

    $totalClientes = Cliente::count() ?? 0;
    $totalVehiculos = Vehiculo::count() ?? 0;

    return view('content.dashboard.dashboards-analytics', [
      'totalFacturado' => $totalFacturado,
      'totalClientes' => $totalClientes,
      'totalVehiculos' => $totalVehiculos,
      'presupuestosPendientes' => 5,
      'encargosActivos' => 3,
      'stockBajo' => 2
    ]);
  }
}
