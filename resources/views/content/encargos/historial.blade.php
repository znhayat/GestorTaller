@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-3 mb-0">
      <span class="text-muted fw-light">Taller /</span> Historial Completo de Trabajos
    </h4>
  </div>

  <!-- Buscador -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <form action="{{ route('encargos.historial') }}" method="GET">
        <div class="row g-3">
          <div class="col-md-10">
            <div class="input-group input-group-merge">
              <span class="input-group-text" id="basic-addon-search31"><i class="ri-search-line"></i></span>
              <input type="text" name="search" class="form-control" placeholder="Buscar por cliente, matrícula, modelo o descripción..." value="{{ $search }}" aria-label="Buscar..." aria-describedby="basic-addon-search31">
            </div>
          </div>
          <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit">Buscar</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabla de Historial -->
  <div class="card shadow-sm">
    <div class="table-responsive text-nowrap">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th class="fw-bold">Última Visita</th>
            <th class="fw-bold">Cliente</th>
            <th class="fw-bold">Teléfono</th>
            <th class="fw-bold">Último Estado</th>
            <th class="fw-bold text-center">Acciones</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse($encargos as $encargo)
          <tr>
            <td>
              <small class="text-muted">{{ $encargo->updated_at->format('d/m/Y') }}</small>
            </td>
            <td>
              <div class="d-flex flex-column">
                <span class="fw-bold text-dark">{{ $encargo->vehiculo->cliente->nombre }} {{ $encargo->vehiculo->cliente->apellido }}</span>
                <small class="text-muted">{{ $encargo->vehiculo->cliente->correo }}</small>
              </div>
            </td>
            <td>
              <span class="text-dark"><i class="ri-phone-line small"></i> {{ $encargo->vehiculo->cliente->telefono }}</span>
            </td>
            <td>
              @php
                $color = match($encargo->estado) {
                    'Entregado' => 'success',
                    'Cancelado' => 'danger',
                    'Esperando Recogida' => 'info',
                    'En Produccion' => 'primary',
                    default => 'warning'
                };
              @endphp
              <span class="badge bg-{{ $color }}">{{ $encargo->estado }}</span>
            </td>
            <td class="text-center">
              <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('clientes.show', $encargo->vehiculo->cliente->id) }}" class="btn btn-sm btn-info" title="Ver Ficha Completa">
                  <i class="ri-eye-line me-1"></i> Ver Ficha
                </a>
                @if(auth()->user()->role == 'admin')
                <a href="{{ route('clientes.edit', $encargo->vehiculo->cliente->id) }}" class="btn btn-sm btn-primary" title="Editar Cliente">
                  <i class="ri-edit-line me-1"></i> Editar
                </a>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center py-5">
              <i class="ri-archive-line fs-1 text-muted opacity-25"></i>
              <p class="mt-2 text-muted">No se han encontrado registros en el historial.</p>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer py-3">
      {{ $encargos->links() }}
    </div>
  </div>
</div>
@endsection
