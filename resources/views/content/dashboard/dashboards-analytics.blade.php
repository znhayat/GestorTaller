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
    <!--/ Congratulations card -->

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
    <!--/ Transactions -->

    <!-- Weekly Overview Chart -->
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
    <!--/ Weekly Overview Chart -->

    <!-- Acciones Rápidas -->
    <div class="col-xl-4 col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('trabajo.create') }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="ri-add-circle-line"></i>. Nuevo Trabajo (Llamada)
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
    <!--/ Acciones Rápidas -->
</div>
<!--/ Data Tables -->
</div>
@endsection