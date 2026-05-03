@extends('layouts/contentNavbarLayout')
@section('title', 'Panel de Gestión - Zana Tapicería')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss'])
<style>
    body { font-family: 'Public Sans', sans-serif; background-color: #f5f5f9; }
    .card-pro { border: none; box-shadow: 0 0.125rem 0.25rem rgba(161, 172, 184, 0.4); border-radius: 0.5rem; transition: all 0.3s ease; }
    .card-pro:hover { box-shadow: 0 0.5rem 1rem rgba(161, 172, 184, 0.45); }
    .card-accent-primary { border-top: 4px solid #696cff; }
    .card-accent-success { border-top: 4px solid #71dd37; }
    .card-accent-warning { border-top: 4px solid #ffab00; }
    .card-accent-info { border-top: 4px solid #03c3ec; }
    .kpi-title { font-size: 0.8rem; font-weight: 700; color: #a1acb8; text-transform: uppercase; letter-spacing: 0.5px; }
    .table-pro thead th { background-color: #fcfdfe; color: #566a7f; border-bottom: 1px solid #e6e8eb; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; }
    .alert-inventory { background-color: #fff1f0; border-left: 4px solid #ff4d4f; border-radius: 8px; }
</style>
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('page-script')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const chartOptions = {
        chart: { height: 320, type: 'donut', fontFamily: 'Public Sans' },
        labels: ['Presupuestos', 'En Taller', 'Finalizados'],
        series: [{{ $presupuestosPendientes }}, {{ $encargosActivos }}, {{ $encargosCompletados }}],
        colors: ['#ffab00', '#696cff', '#71dd37'],
        stroke: { width: 0 },
        legend: { position: 'bottom', fontFamily: 'Public Sans', fontWeight: 500 },
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        value: { fontSize: '1.75rem', fontWeight: 700, color: '#32475C', fontFamily: 'Public Sans', offsetY: -10 },
                        name: { color: '#697a8d', fontFamily: 'Public Sans', offsetY: 20 },
                        total: { show: true, label: 'TOTAL TRABAJOS', fontSize: '0.7rem', fontWeight: 600, color: '#a1acb8', fontFamily: 'Public Sans' }
                    }
                }
            }
        }
    };
    const chartEl = document.querySelector('#statusChart');
    if (chartEl) new ApexCharts(chartEl, chartOptions).render();
});
</script>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- HEADER -->
    <div class="row align-items-center mb-4">
        <div class="col-md-7">
            <h4 class="fw-bold mb-1">Resumen General de Operaciones</h4>
            <p class="text-muted small mb-0">Zana Tapicería - Estado en tiempo real al {{ date('d/m/Y H:i') }}</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <a href="{{ route('trabajo.create') }}" class="btn btn-primary shadow-sm px-4">
                <i class="ri-add-line me-1"></i> NUEVO TRABAJO
            </a>
        </div>
    </div>

    <!-- ALERTAS DE INVENTARIO -->
    @if($materialesBajoStock->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-inventory d-flex align-items-center p-3 shadow-sm" role="alert">
                <i class="ri-error-warning-fill text-danger me-3 fs-3"></i>
                <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1 fw-bold text-danger">Atención: Stock Crítico</h6>
                    <p class="mb-0 small text-dark">Tienes <strong>{{ $materialesBajoStock->count() }}</strong> materiales por debajo del mínimo. <a href="{{ url('materiales') }}" class="fw-bold text-danger text-decoration-underline">Revisar inventario ahora</a>.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- KPI ROW -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-pro card-accent-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="kpi-title">Por Aceptar</div>
                        <i class="ri-history-line text-success fs-4"></i>
                    </div>
                    <h3 class="fw-bold mt-2 mb-0">{{ number_format($dineroPendiente, 2) }} €</h3>
                    <small class="text-muted">Valor de presupuestos enviados</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-pro card-accent-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="kpi-title">Carga de Trabajo</div>
                        <i class="ri-tools-line text-primary fs-4"></i>
                    </div>
                    <h3 class="fw-bold mt-2 mb-0">{{ $encargosActivos }}</h3>
                    <small class="text-muted">Vehículos en proceso técnico</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-pro card-accent-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="kpi-title">Recepciones Hoy</div>
                        <i class="ri-calendar-event-line text-warning fs-4"></i>
                    </div>
                    <h3 class="fw-bold mt-2 mb-0">{{ $citasHoy->count() }}</h3>
                    <small class="text-muted">Entradas programadas para hoy</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-pro card-accent-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="kpi-title">Cartera Clientes</div>
                        <i class="ri-user-star-line text-info fs-4"></i>
                    </div>
                    <h3 class="fw-bold mt-2 mb-0">{{ $totalClientes }}</h3>
                    <small class="text-muted">Total clientes registrados</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- AGENDA -->
        <div class="col-lg-8">
            <div class="card card-pro h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3 border-bottom">
                    <h5 class="mb-0 fw-bold">Agenda Operativa de Hoy</h5>
                    <a href="{{ route('citas.calendario') }}" class="btn btn-sm btn-outline-secondary">Ver Agenda Completa</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-pro align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Hora</th>
                                    <th>Vehículo / Trabajo</th>
                                    <th>Cliente</th>
                                    <th class="text-end pe-4">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($citasHoy as $c)
                                <tr>
                                    <td class="ps-4 fw-bold text-primary">{{ \Carbon\Carbon::parse($c->hora_cita)->format('H:i') }}</td>
                                    <td>
                                        <span class="d-block fw-bold text-dark">{{ $c->vehiculo->marca }} {{ $c->vehiculo->modelo }}</span>
                                        <small class="text-muted">{{ Str::limit($c->descripcion, 40) }}</small>
                                    </td>
                                    <td>{{ $c->vehiculo->cliente->nombre }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('encargos.edit', $c->id) }}" class="btn btn-sm btn-icon btn-primary"><i class="ri-arrow-right-up-line"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted small">No hay recepciones programadas para hoy.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHART -->
        <div class="col-lg-4">
            <div class="card card-pro h-100">
                <div class="card-header py-3 border-bottom text-center">
                    <h5 class="mb-0 fw-bold">Estado de los Trabajos</h5>
                </div>
                <div class="card-body d-flex align-items-center">
                    <div id="statusChart" class="w-100"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- ÚLTIMA ACTIVIDAD -->
        <div class="col-md-6">
            <div class="card card-pro h-100">
                <div class="card-header py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Últimos Encargos</h5>
                    <a href="{{ route('encargos.index') }}" class="text-primary small">Ver todos</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-pro mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Fecha</th>
                                    <th>Vehículo</th>
                                    <th class="text-end pe-4">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimosEncargos as $ue)
                                <tr>
                                    <td class="ps-4 small">{{ \Carbon\Carbon::parse($ue->created_at)->format('d/m') }}</td>
                                    <td><span class="fw-bold">{{ $ue->vehiculo->marca }}</span></td>
                                    <td class="text-end pe-4">
                                        <span class="badge bg-label-primary px-2 py-1 small">{{ strtoupper($ue->estado) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ÚLTIMAS FACTURAS -->
        <div class="col-md-6">
            <div class="card card-pro h-100">
                <div class="card-header py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Facturación Reciente</h5>
                    <a href="{{ url('facturas') }}" class="text-success small">Ver historial</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-pro mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Factura</th>
                                    <th>Cliente</th>
                                    <th class="text-end pe-4">Importe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimasFacturas as $f)
                                <tr>
                                    <td class="ps-4 fw-bold">#{{ $f->numero_factura }}</td>
                                    <td>{{ $f->encargo->vehiculo->cliente->nombre ?? 'N/A' }}</td>
                                    <td class="text-end pe-4 fw-bold text-dark">{{ number_format($f->importe_total, 2) }} €</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">No se han emitido facturas recientemente.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection