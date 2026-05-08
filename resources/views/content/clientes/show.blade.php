@extends('layouts/contentNavbarLayout')

@section('title', 'Historial del Cliente')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Clientes /</span> Historial de {{ $cliente->nombre }}
  </h4>

  <div class="row">
    <!-- User Sidebar -->
    <div class="col-xl-4 col-lg-5 col-md-5">
      <!-- User Card -->
      <div class="card mb-4">
        <div class="card-body">
          <div class="user-avatar-section">
            <div class="d-flex align-items-center flex-column">
              <div class="avatar avatar-xl mb-3">
                <span class="avatar-initial rounded-circle bg-primary fs-2">{{ strtoupper(substr($cliente->nombre, 0, 1)) }}</span>
              </div>
              <div class="user-info text-center">
                <h4 class="mb-2">{{ $cliente->nombre }} {{ $cliente->apellido }}</h4>
                <span class="badge bg-label-secondary">Cliente del Taller</span>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-around flex-wrap my-4 py-3">
            <div class="d-flex align-items-start me-4 mt-3 gap-3">
              <div>
                <h5 class="mb-0 text-center">{{ $cliente->vehiculos->count() }}</h5>
                <span>Vehículos</span>
              </div>
            </div>
            <div class="d-flex align-items-start mt-3 gap-3">
              <div>
                <h5 class="mb-0 text-center">{{ $cliente->vehiculos->flatMap->encargos->count() }}</h5>
                <span>Trabajos</span>
              </div>
            </div>
          </div>
          <h5 class="pb-2 border-bottom mb-4">Detalles</h5>
          <div class="info-container">
            <ul class="list-unstyled">
              <li class="mb-3">
                <span class="fw-bold me-2 text-heading">Teléfono:</span>
                <span>{{ $cliente->telefono }}</span>
              </li>
              <li class="mb-3">
                <span class="fw-bold me-2 text-heading">Email:</span>
                <span>{{ $cliente->correo ?? 'N/A' }}</span>
              </li>
            </ul>
            <div class="d-flex justify-content-center pt-3">
              <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-primary me-3">Editar Datos</a>
              <a href="{{ route('encargos.historial') }}" class="btn btn-outline-secondary">Volver</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ User Sidebar -->

    <!-- User Content -->
    <div class="col-xl-8 col-lg-7 col-md-7">
      <!-- Activity Timeline -->
      @foreach($cliente->vehiculos as $vehiculo)
      <div class="card mb-4">
        <h5 class="card-header border-bottom">
          {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
        </h5>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr>
                <th class="ps-4">Trabajo</th>
                <th class="text-center">Estado</th>
                <th class="text-end">Importe</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @foreach($vehiculo->encargos->sortByDesc('created_at') as $encargo)
              <tr>
                <td class="ps-4">
                  <span class="fw-bold">#{{ $encargo->id }}</span><br>
                  <small class="text-muted">{{ $encargo->created_at->format('d/m/Y') }}</small>
                </td>
                <td class="text-center">
                  @php
                    $badge = match($encargo->estado) {
                      'Entregado' => 'success',
                      'Cancelado' => 'danger',
                      default => 'primary'
                    };
                  @endphp
                  <span class="badge bg-label-{{ $badge }}">{{ $encargo->estado }}</span>
                </td>
                <td class="text-end fw-bold">
                  @if($encargo->factura)
                    {{ number_format($encargo->factura->importe_total, 2) }}€
                  @else
                    —
                  @endif
                </td>
                <td class="text-center">
                  <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('encargos.show', $encargo->id) }}" class="btn btn-sm btn-primary">
                      <i class="ri-eye-line me-1"></i> Ver Ficha
                    </a>
                    @if($encargo->factura)
                    <a href="{{ route('facturas.imprimir', $encargo->factura->id) }}" class="btn btn-sm btn-outline-info" target="_blank">
                      <i class="ri-printer-line me-1"></i> Factura
                    </a>
                    @endif
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @endforeach
    </div>
    <!--/ User Content -->
  </div>
</div>
@endsection
