@extends('layouts/contentNavbarLayout')
@section('title', 'Panel de Control - Global')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('page-script')
@vite(['resources/assets/js/dashboards-analytics.js'])
<script>
document.addEventListener("DOMContentLoaded", function () {
    const cardColor = "#fff";
    const headingColor = "#566a7f";
    const legendColor = "#8290a3";

    // Configuració del gràfic interactiu (Donut)
    const chartOptions = {
        chart: {
            height: 250,
            type: 'donut',
            fontFamily: 'Inter, sans-serif'
        },
        labels: ['Pendientes', 'En Taller', 'Completados'],
        series: [{{ $presupuestosPendientes }}, {{ $encargosActivos }}, {{ $encargosCompletados }}],
        colors: ['#ffb400', '#696cff', '#71dd37'],
        stroke: {
            width: 5,
            colors: ['#fff']
        },
        dataLabels: { enabled: false },
        legend: { show: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        value: {
                            fontSize: '2rem',
                            color: '#566a7f',
                            fontWeight: 600,
                        },
                        name: { color: '#8290a3' },
                        total: {
                            show: true,
                            showAlways: true,
                            label: 'Volumen',
                            fontSize: '1rem',
                            color: '#8290a3'
                        }
                    }
                }
            }
        }
    };

    const rendimientoChartEl = document.querySelector('#rendimientoChart');
    if (rendimientoChartEl && typeof ApexCharts !== 'undefined') {
        const rendimientoChart = new ApexCharts(rendimientoChartEl, chartOptions);
        rendimientoChart.render();
    }
});
</script>
@endsection

@section('content')
<div class="row gy-4">

    <!-- ======================= ROW 1: RESUMEN DE ACTIVIDAD ======================= -->
    
    <!-- Balance Financiero -->
    <div class="col-md-12 col-lg-4">
        <div class="card h-100 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <div class="card-body position-relative overflow-hidden">
                <i class="ri-wallet-3-line position-absolute text-white opacity-25" style="font-size: 10rem; top: -20px; right: -20px;"></i>
                <h5 class="card-title mb-1 text-white fw-medium">Balance Global del Taller</h5>
                <p class="mb-4 text-white-50" style="font-size: 0.9rem;">Facturación histórica validada</p>
                <h1 class="text-white mb-2 fw-bolder">{{ number_format($totalFacturado, 2) }} €</h1>
                <p class="mb-4 text-white-50"><i class="ri-checkbox-circle-fill text-success pe-1"></i> Sincronizado en tiempo real</p>
                <div class="mt-auto position-relative z-1">
                    <a href="{{ url('facturas') }}" class="btn btn-outline-light btn-sm fw-bold shadow-sm">Acceder a Contabilidad</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Rendimiento Chart -->
    <div class="col-lg-8 col-md-12">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title m-0 me-2 mb-1">Análisis de Volumen de Trabajo</h5>
                    <small class="text-muted">Distribución de estados operativos del mes</small>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row w-100 h-100 align-items-center">
                    <div class="col-md-6 order-2 order-md-1">
                        <ul class="p-0 m-0">
                            <li class="d-flex mb-4 pb-1 align-items-center">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-warning"><i class="ri-hourglass-2-line"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2"><h6 class="mb-0">Pdt. Presupuestar o Aceptar</h6></div>
                                    <div class="user-progress"><div class="fw-semibold fs-5">{{ $presupuestosPendientes }}</div></div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1 align-items-center">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="ri-tools-line"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2"><h6 class="mb-0">Vehículos en Taller</h6></div>
                                    <div class="user-progress"><div class="fw-semibold fs-5">{{ $encargosActivos }}</div></div>
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-success"><i class="ri-check-double-line"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2"><h6 class="mb-0">Encargos Completados</h6></div>
                                    <div class="user-progress"><div class="fw-semibold fs-5">{{ $encargosCompletados }}</div></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 order-1 order-md-2 d-flex justify-content-center">
                        <div id="rendimientoChart" style="min-height: 250px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================= ROW 2: CONTROL Y ALERTAS ======================= -->
    
    <!-- Alertas de Citas Consolidadas -->
    <div class="col-xl-6 col-lg-6 col-md-12">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between pb-0">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Control Mando - Agenda</h5>
                </div>
                <div>
                    <span class="badge bg-label-primary rounded-pill">{{ $totalCitasPendientes }} Pendiente(s)</span>
                </div>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0 mt-4">
                    <!-- Atrasadas -->
                    @if($citasAtrasadas > 0)
                    <li class="d-flex mb-4 align-items-center pb-2 border-bottom">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-danger"><i class="ri-alarm-warning-line"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-0 font-weight-bold text-danger">Citas Atrasadas</h6>
                                <small class="text-muted">Revísalas lo antes posible</small>
                            </div>
                            <div class="user-progress">
                                <small class="fw-semibold text-danger">{{ $citasAtrasadas }} cita(s)</small>
                            </div>
                        </div>
                    </li>
                    @endif

                    <!-- Hoy -->
                    <li class="d-flex mb-4 align-items-center pb-2 border-bottom">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-warning"><i class="ri-calendar-event-fill"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-0 font-weight-bold">Programadas Hoy</h6>
                                <small class="text-muted">Vehículos entrando al local</small>
                            </div>
                            <div class="user-progress">
                                <small class="fw-semibold">{{ $citasHoy->count() }} cita(s)</small>
                            </div>
                        </div>
                    </li>

                    <!-- Mañana -->
                    <li class="d-flex mb-4 align-items-center pb-2 border-bottom">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-info"><i class="ri-calendar-line"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-0 font-weight-bold">Para Mañana</h6>
                                <small class="text-muted">Afluencia prevista</small>
                            </div>
                            <div class="user-progress">
                                <small class="fw-semibold">{{ $citasManana }} cita(s)</small>
                            </div>
                        </div>
                    </li>

                    <!-- Próximos 7 días -->
                    <li class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-secondary"><i class="ri-calendar-todo-line"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-0 font-weight-bold">Próximos 7 días</h6>
                                <small class="text-muted">Planificación a corto plazo</small>
                            </div>
                            <div class="user-progress">
                                <small class="fw-semibold">{{ $citasProximas->count() }} cita(s)</small>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="mt-4 text-center">
                    <a href="{{ route('citas.calendario') }}" class="btn btn-sm btn-outline-primary w-100">Ver Calendario Completo</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas de Inventario -->
    <div class="col-xl-3 col-lg-3 col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Inventario</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <div class="avatar-initial bg-label-dark rounded-circle"><i class="ri-file-shield-2-line"></i></div>
                        </div>
                        <div>
                            <h6 class="mb-0">Base de Materiales</h6>
                            <small class="text-muted">En catálogo</small>
                        </div>
                    </div>
                </div>
                <h3 class="mb-2">{{ $totalMateriales }} Registros</h3>
                <p class="mb-4 text-muted"><small>Mantén tu catálogo al día para realizar presupuestos precisos y extraer Excel correctos.</small></p>
                <div class="d-grid mt-auto">
                    <a href="{{ url('materiales') }}" class="btn btn-outline-dark btn-sm">Gestionar Archivo</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="col-xl-3 col-lg-3 col-md-6">
        <div class="card h-100 bg-primary text-white" style="box-shadow: 0 4px 15px rgba(105, 108, 255, 0.4);">
            <div class="card-header border-0 pb-0">
                <h5 class="card-title m-0 text-white">Accesos Directos</h5>
            </div>
            <div class="card-body mt-4">
                <div class="d-grid gap-3">
                    <a href="{{ route('trabajo.create') }}" class="btn btn-light text-primary w-100 d-flex align-items-center justify-content-center gap-2 fw-bold shadow-sm">
                        <i class="ri-add-circle-fill"></i> Crear Nuevo Trabajo
                    </a>
                    <a href="{{ route('encargos.recepcion') }}" class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="ri-drag-drop-line"></i> Panel R. Comercial
                    </a>
                    <a href="{{ route('encargos.produccion') }}" class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="ri-hammer-fill"></i> Panel Taller
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================= ROW 3: LÍNEA DE ACTIVIDAD ======================= -->
    <div class="col-12 mt-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between border-bottom pb-4 mb-4">
                <div>
                    <h5 class="card-title m-0"><i class="ri-history-line me-2 text-primary"></i>Línea de Actividad Reciente</h5>
                    <small class="text-muted">Desglose de los últimos 5 vehículos registrados o intervenidos</small>
                </div>
                <a href="{{ route('encargos.index') }}" class="btn btn-sm btn-primary shadow-sm"><i class="ri-folder-reduce-line me-1"></i> Explorar Archivo</a>
            </div>
            <div class="card-body">
                @if($ultimosEncargos->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="ri-inbox-archive-line fs-1 mb-2 d-block text-secondary"></i> 
                        <span class="fs-5">No hay actividad reciente en el sistema.</span>
                    </div>
                @else
                    <ul class="timeline ms-3 mb-0" style="list-style: none; padding-left: 0; position: relative;">
                        <!-- Línea vertical del timeline -->
                        <div class="position-absolute" style="top: 15px; left: 16px; width: 2px; height: calc(100% - 30px); background: #e0e0e0; z-index: 1;"></div>
                        
                        @foreach($ultimosEncargos as $index => $ue)
                            @php 
                                $color = 'primary';
                                $icon = 'ri-tools-line';
                                if($ue->estado == 'Entregado' || $ue->estado == 'Finalizado') { $color = 'success'; $icon = 'ri-check-double-line'; }
                                elseif($ue->estado == 'Cita Agendada' || $ue->estado == 'Pendiente') { $color = 'secondary'; $icon = 'ri-alarm-line'; }
                                elseif($ue->estado == 'Esperando Recogida' || $ue->estado == 'Presupuesto Enviado') { $color = 'warning'; $icon = 'ri-feedback-line'; }
                            @endphp
                            <li class="position-relative mb-4 d-flex" style="z-index: 2;">
                                <!-- Bullet / Indicador Timeline -->
                                <div class="d-flex justify-content-center align-items-center bg-white" style="width: 34px; height: 34px; border-radius: 50%; box-shadow: 0 0 0 4px #fff; border: 2px solid var(--bs-{{ $color }}); z-index: 3;">
                                    <i class="{{ $icon }} text-{{ $color }}" style="font-size: 15px;"></i>
                                </div>
                                
                                <!-- Contenido Timeline Ficha -->
                                <div class="ms-4 p-4 rounded w-100 shadow-sm" style="background-color: #f8f9fa; border-left: 4px solid var(--bs-{{ $color }});">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 text-dark fw-bold fs-5">
                                            {{ $ue->vehiculo->marca }} {{ $ue->vehiculo->modelo }}
                                        </h6>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-label-{{ $color }} px-3 py-1 me-3">{{ strtoupper($ue->estado) }}</span>
                                            <small class="text-muted"><i class="ri-time-line me-1"></i>{{ \Carbon\Carbon::parse($ue->created_at)->locale('es')->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex mb-3 align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-dark"><i class="ri-user-line"></i></span>
                                        </div>
                                        <span class="text-body fw-medium">{{ $ue->vehiculo->cliente->nombre }} {{ $ue->vehiculo->cliente->apellido }}</span>
                                    </div>
                                    
                                    <div class="bg-white p-3 rounded border text-wrap text-muted" style="font-size: 14px;">
                                        <i class="ri-quote-text-line me-1 text-secondary"></i> {{ Str::limit($ue->descripcion, 200) }}
                                    </div>
                                    
                                    <div class="mt-3 text-end">
                                        <a href="{{ route('encargos.edit', $ue->id) }}" class="btn btn-sm btn-{{ $color }} rounded-pill px-4 shadow-sm">Abrir Expediente <i class="ri-arrow-right-line ms-1"></i></a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection