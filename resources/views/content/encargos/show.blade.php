@extends('layouts/contentNavbarLayout')

@section('title', 'Detalle del Encargo #' . $encargo->id)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Historial /</span> Detalle de Trabajo #{{ $encargo->id }}
  </h4>

  <div class="row">
    <!-- Main Content -->
    <div class="col-xl-8 col-lg-7 col-md-12">
      <!-- Description Card -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Descripción del Trabajo</h5>
          <span class="badge bg-label-primary">{{ $encargo->estado }}</span>
        </div>
        <div class="card-body">
          <p class="mb-0">{{ $encargo->descripcion }}</p>
        </div>
      </div>

      <!-- Timeline (Standard Materio) -->
      <div class="card">
        <h5 class="card-header">Cronología</h5>
        <div class="card-body">
          <ul class="timeline mt-3">
            <li class="timeline-item timeline-item-transparent border-primary">
              <span class="timeline-point timeline-point-primary"></span>
              <div class="timeline-event">
                <div class="timeline-header mb-1">
                  <h6 class="mb-0">Entrada en Taller</h6>
                  <small class="text-muted">{{ $encargo->fecha_entrada ? \Carbon\Carbon::parse($encargo->fecha_entrada)->format('d/m/Y') : '—' }}</small>
                </div>
                <p class="mb-0">Recepción del vehículo.</p>
              </div>
            </li>
            <li class="timeline-item timeline-item-transparent border-info">
              <span class="timeline-point timeline-point-info"></span>
              <div class="timeline-event">
                <div class="timeline-header mb-1">
                  <h6 class="mb-0">Fase de Trabajo</h6>
                  <small class="text-muted">Producción</small>
                </div>
                <p class="mb-0">Proceso de tapizado/reparación.</p>
              </div>
            </li>
            <li class="timeline-item timeline-item-transparent border-{{ $encargo->fecha_salida ? 'success' : 'secondary' }} pb-0">
              <span class="timeline-point timeline-point-{{ $encargo->fecha_salida ? 'success' : 'secondary' }}"></span>
              <div class="timeline-event pb-0">
                <div class="timeline-header mb-1">
                  <h6 class="mb-0">Entrega</h6>
                  <small class="text-muted">{{ $encargo->fecha_salida ? \Carbon\Carbon::parse($encargo->fecha_salida)->format('d/m/Y') : 'Pendiente' }}</small>
                </div>
                <p class="mb-0">Cierre de orden de trabajo.</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="col-xl-4 col-lg-5 col-md-12">
      <!-- Financial Summary -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0 text-primary">Resumen Económico</h5>
        </div>
        <div class="card-body">
          @if($encargo->presupuesto)
          <div class="d-flex justify-content-between mb-3">
            <span>Materiales:</span>
            <span class="fw-bold">{{ number_format($encargo->presupuesto->precio_materiales, 2) }} €</span>
          </div>
          <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
            <span>Mano de Obra:</span>
            <span class="fw-bold">{{ number_format($encargo->presupuesto->precio_horas, 2) }} €</span>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">TOTAL:</h5>
            <h4 class="mb-0 text-primary fw-bold">{{ number_format($encargo->presupuesto->total, 2) }} €</h4>
          </div>
          @else
          <p class="text-muted mb-0">No hay presupuesto disponible.</p>
          @endif
        </div>
        @if($encargo->factura)
        <div class="card-footer bg-label-{{ $encargo->factura->pagado ? 'success' : 'danger' }} text-center">
          <span class="fw-bold">{{ $encargo->factura->pagado ? 'COBRADO' : 'PENDIENTE DE PAGO' }}</span>
        </div>
        @endif
      </div>

      <!-- Vehicle & Client Info -->
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Información Adicional</h5>
        </div>
        <div class="card-body">
          <ul class="list-unstyled mb-0">
            <li class="mb-3">
              <span class="fw-bold">Cliente:</span> {{ $encargo->vehiculo->cliente->nombre }} {{ $encargo->vehiculo->cliente->apellido }}
            </li>
            <li class="mb-3">
              <span class="fw-bold">Vehículo:</span> {{ $encargo->vehiculo->marca }} {{ $encargo->vehiculo->modelo }}
            </li>
          </ul>
          <hr>
          <div class="d-grid">
            @if($encargo->factura)
            <a href="{{ route('facturas.imprimir', $encargo->factura->id) }}" class="btn btn-outline-info mb-2" target="_blank">
              <i class="ri-printer-line me-1"></i> Imprimir Factura
            </a>
            @endif
            <a href="{{ route('clientes.show', $encargo->vehiculo->cliente_id) }}" class="btn btn-outline-secondary">
              <i class="ri-arrow-left-line me-1"></i> Volver a la Ficha
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection