<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Encargo;
use App\Models\Presupuesto;
use App\Models\Material;
use Carbon\Carbon;

class Analytics extends Controller
{
  public function index()
  {
    // Dinero en presupuestos pendientes de aceptar
    $dineroPendiente = Presupuesto::where('aceptado', 0)
        ->orWhereNull('aceptado')
        ->sum('total') ?? 0;

    $totalClientes = Cliente::count() ?? 0;
    $encargosActivos = Encargo::whereNotIn('estado', ['Finalizado', 'Entregado', 'Cancelado'])->count();
    $encargosCompletados = Encargo::whereIn('estado', ['Finalizado', 'Entregado'])->count();
    $presupuestosPendientes = Presupuesto::where('aceptado', 0)->orWhereNull('aceptado')->count();
    $totalMateriales = Material::count();

    // Dinero real en caja (Facturas pagadas)
    $ingresosReales = Factura::where('pagado', true)->sum('importe_total') ?? 0;
    
    // Facturación pendiente de cobro (Trabajos entregados pero no pagados)
    $ingresosPendientesCobro = Factura::where('pagado', false)->sum('importe_total') ?? 0;

    // ========== ALERTAS DE CITAS ==========

    // Citas para hoy
    $citasHoy = Encargo::with('vehiculo.cliente')
      ->whereDate('cita_revision', Carbon::today())
      ->where('estado', 'Cita Agendada')
      ->get();

    // Citas para mañana
    $citasManana = Encargo::with('vehiculo.cliente')
      ->whereDate('cita_revision', Carbon::tomorrow())
      ->where('estado', 'Cita Agendada')
      ->count();

    // Citas próximas (próximos 7 días, excluyendo hoy)
    $citasProximas = Encargo::with('vehiculo.cliente')
      ->whereBetween('cita_revision', [Carbon::tomorrow(), Carbon::today()->addDays(7)])
      ->where('estado', 'Cita Agendada')
      ->orderBy('cita_revision', 'asc')
      ->get();

    // Citas atrasadas (fecha pasada y no se ha revisado)
    $citasAtrasadas = Encargo::with('vehiculo.cliente')
      ->whereDate('cita_revision', '<', Carbon::today())
      ->where('estado', 'Cita Agendada')
      ->count();

    // Total de citas pendientes
    $totalCitasPendientes = Encargo::where('estado', 'Cita Agendada')
      ->whereNotNull('cita_revision')
      ->count();

    // Entregas Pendientes / Urgentes en Producción
    $entregasUrgentes = Encargo::with('vehiculo.cliente')
      ->whereIn('estado', ['En Produccion', 'Esperando Recogida'])
      ->where(function($q) {
          $q->whereDate('cita_recogida', '<=', Carbon::today()->addDays(2))
            ->orWhere('estado', 'Esperando Recogida');
      })
      ->get();

    // Materiales con bajo stock (Alertas)
    $materialesBajoStock = Material::whereColumn('stock', '<=', 'stock_minimo')
        ->where('stock_minimo', '>', 0)
        ->get();

    // Últimas 5 facturas
    $ultimasFacturas = Factura::latest()->take(5)->get();

    // Últimos 5 encargos
    $ultimosEncargos = Encargo::with(['vehiculo.cliente', 'presupuesto'])->latest()->take(5)->get();

    return view('content.dashboard.dashboards-analytics', [
      'dineroPendiente' => $dineroPendiente,
      'totalClientes' => $totalClientes,
      'presupuestosPendientes' => $presupuestosPendientes,
      'encargosActivos' => $encargosActivos,
      'encargosCompletados' => $encargosCompletados,
      'totalMateriales' => $totalMateriales,
      // Alertas
      'citasHoy' => $citasHoy,
      'citasManana' => $citasManana,
      'citasProximas' => $citasProximas,
      'citasAtrasadas' => $citasAtrasadas,
      'ultimasFacturas' => $ultimasFacturas,
      'materialesBajoStock' => $materialesBajoStock,
      'totalCitasPendientes' => $totalCitasPendientes,
      'entregasUrgentes' => $entregasUrgentes,
      'ultimosEncargos' => $ultimosEncargos,
      'ingresosReales' => $ingresosReales,
      'ingresosPendientesCobro' => $ingresosPendientesCobro,
    ]);
  }
}
