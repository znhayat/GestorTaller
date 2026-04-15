@extends('layouts/contentNavbarLayout')
@section('title', 'Dashboard - Analytics')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('page-script')
@vite(['resources/assets/js/dashboards-analytics.js'])
@endsection

@section('content')
<div class="row gy-6">

    <!-- ALERTAS DE CITAS - SECCIÓN PROFESIONAL -->
    @if($totalCitasPendientes > 0)
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-label-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="ri-calendar-check-line me-2"></i>
                            Gestión de Citas de Revisión
                        </h5>
                        <small class="text-muted">Citas agendadas para revisión de presupuesto</small>
                    </div>
                    <span class="badge bg-primary rounded-pill fs-6 px-3 py-2">
                        {{ $totalCitasPendientes }} Cita(s) Pendiente(s)
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">

                    <!-- Citas Atrasadas - Alerta Roja -->
                    @if($citasAtrasadas > 0)
                    <div class="col-md-4">
                        <div class="alert alert-danger mb-0 d-flex align-items-center">
                            <i class="ri-error-warning-line fs-1 me-3"></i>
                            <div>
                                <h6 class="mb-0">¡Citas Atrasadas!</h6>
                                <p class="mb-0 small">{{ $citasAtrasadas }} cita(s) pasaron su fecha de revisión</p>
                                <a href="{{ route('encargos.recepcion') }}" class="small">Ver pendientes →</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Citas para Hoy - Alerta Amarilla -->
                    @if($citasHoy->count() > 0)
                    <div class="col-md-4">
                        <div class="alert alert-warning mb-0">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ri-calendar-todo-line fs-1 me-3"></i>
                                <div>
                                    <h6 class="mb-0">Citas para HOY</h6>
                                    <p class="mb-0 small">{{ $citasHoy->count() }} cita(s) programadas</p>
                                </div>
                            </div>
                            <div class="mt-2">
                                @foreach($citasHoy as $cita)
                                <div class="d-flex justify-content-between align-items-center small mb-1">
                                    <span>
                                        <i class="ri-car-line me-1"></i>
                                        {{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}
                                    </span>
                                    <span class="text-primary">
                                        {{ $cita->vehiculo->cliente->nombre }}
                                    </span>
                                    <a href="{{ route('encargos.recepcion') }}" class="btn btn-xs btn-warning">Atender</a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Citas para Mañana -->
                    @if($citasManana > 0)
                    <div class="col-md-4">
                        <div class="alert alert-info mb-0">
                            <div class="d-flex align-items-center">
                                <i class="ri-calendar-line fs-1 me-3"></i>
                                <div>
                                    <h6 class="mb-0">Mañana</h6>
                                    <p class="mb-0 small">{{ $citasManana }} cita(s) programadas</p>
                                    <a href="{{ route('encargos.recepcion') }}" class="small">Ver detalles →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Citas Próximas - Lista detallada -->
                @if($citasProximas->count() > 0)
                <div class="mt-4">
                    <h6 class="mb-3"><i class="ri-calendar-event-line me-1"></i> Próximas Citas (próximos 7 días)</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Vehículo</th>
                                    <th>Teléfono</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($citasProximas as $cita)
                                <tr>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ date('d/m/Y', strtotime($cita->cita_revision)) }}
                                        </span>
                                    </td>
                                    <td>{{ $cita->vehiculo->cliente->nombre }} {{ $cita->vehiculo->cliente->apellido }}</td>
                                    <td>{{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}</td>
                                    <td>{{ $cita->vehiculo->cliente->telefono }}</td>
                                    <td>
                                        <a href="{{ route('encargos.recepcion') }}" class="btn btn-sm btn-primary">
                                            Gestionar
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Congratulations card -->
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-body text-nowrap">
                <h5 class="card-title mb-0 flex-wrap text-nowrap">¡Buen trabajo, {{ Auth::user()->name }}! 🎉</h5>
                <p class="mb-2">Facturación total del taller</p>
                <h4 class="text-primary mb-0">{{ number_format($totalFacturado, 2) }}€</h4>
                <p class="mb-2">Estado: Activo </p>
                <a href="{{ url('facturas') }}" class="btn btn-sm btn-primary">Ver Facturas</a>
            </div>
            <img src="{{ asset('assets/img/illustrations/trophy.png') }}" class="position-absolute bottom-0 end-0 me-5 mb-5" width="83" alt="view sales" />
        </div>
    </div>

    <!-- Transactions -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title m-0 me-2">Resumen de Actividad</h5>
            </div>
            <div class="card-body pt-lg-10">
                <div class="row g-6">
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <div class="avatar-initial bg-primary rounded shadow-xs">
                                    <i class="icon-base ri ri-group-line icon-24px"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <p class="mb-0">Clientes</p>
                                <h5 class="mb-0">{{ $totalClientes }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <div class="avatar-initial bg-success rounded shadow-xs">
                                    <i class="icon-base ri ri-car-line icon-24px"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <p class="mb-0">Vehículos</p>
                                <h5 class="mb-0">{{ $totalVehiculos }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <div class="avatar-initial bg-warning rounded shadow-xs">
                                    <i class="icon-base ri ri-file-list-3-line icon-24px"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <p class="mb-0">Pdt. Aceptar</p>
                                <h5 class="mb-0">{{ $presupuestosPendientes }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <div class="avatar-initial bg-info rounded shadow-xs">
                                    <i class="icon-base ri ri-tools-line icon-24px"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <p class="mb-0">En Reparación</p>
                                <h5 class="mb-0">{{ $encargosActivos }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas de Inventario -->
    <div class="col-xl-4 col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Alertas de Inventario</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-4">
                            <div class="avatar-initial bg-label-danger rounded-circle"><i class="ri-error-warning-line"></i></div>
                        </div>
                        <div>
                            <h6 class="mb-0">Materiales en catálogo</h6>
                            <p class="mb-0">Registrados en la base de datos</p>
                        </div>
                    </div>
                    <div class="text-end">
                        <h6 class="mb-1 text-primary">{{ $totalMateriales }}</h6>
                        <small class="text-body-secondary">Items</small>
                    </div>
                </div>
                <div class="d-grid mt-4">
                    <a href="{{ url('materiales') }}" class="btn btn-outline-danger btn-sm">Gestionar Stock</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="col-xl-4 col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('trabajo.create') }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="ri-add-circle-line"></i> Nuevo Trabajo (Llamada)
                    </a>
                    <a href="{{ route('encargos.recepcion') }}" class="btn btn-outline-info w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="ri-phone-line"></i> Tablero Recepción
                    </a>
                    <a href="{{ route('encargos.produccion') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="ri-tools-line"></i> Tablero Taller
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection