@extends('layouts/contentNavbarLayout')

@section('title', 'Facturación')

@section('content')
<style>
    .search-container { min-width: 250px; }
    .search-icon { top: 50%; transform: translateY(-50%); left: 15px; color: #a1acb8; }
    .clear-search { top: 50%; transform: translateY(-50%); right: 10px; color: #a1acb8; cursor: pointer; }
</style>

<div class="card">
  <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <h5 class="mb-0">Historial de Facturas</h5>
    <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
      <!-- Buscador -->
      <form method="GET" action="{{ route('facturas.index') }}" class="d-flex position-relative me-2 search-container">
        <label for="search-facturas" class="visually-hidden">Buscar facturas</label>
        <input type="text" id="search-facturas" name="search" class="form-control ps-5 pe-4 w-100" placeholder="Buscar por cliente o vehículo..." value="{{ request('search') }}">
        <i class="ri-search-line position-absolute search-icon" aria-hidden="true"></i>
        @if(request('search'))
        <a href="{{ route('facturas.index') }}" class="position-absolute clear-search" title="Limpiar búsqueda" aria-label="Limpiar búsqueda">
          <i class="ri-close-circle-line" aria-hidden="true"></i>
        </a>
        @endif
      </form>

      <button class="btn btn-outline-success btn-export-csv" data-filename="facturas"> <!-- Nou boton Export Excel -->
        <i class="ri-file-excel-line me-1"></i> Exportar
      </button>
      <a href="{{ route('facturas.create') }}" class="btn btn-primary"><i class="ri-add-line me-1"></i> Nueva Factura</a>
    </div>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table table-hover" aria-label="Historial de facturas">
      <thead>
        <tr>
          <th># ID</th>
          <th>Cliente / Vehículo</th>
          <th>Importe Total</th>
          <th>Estado</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($facturas as $f)
        <tr>
          <td><strong class="text-primary">#{{ $f->id }}</strong></td>
          <td>
            <span class="fw-medium">{{ $f->encargo->vehiculo->cliente->nombre }}</span><br>
            <small class="text-muted">{{ $f->encargo->vehiculo->marca }} {{ $f->encargo->vehiculo->modelo }}</small>
          </td>
          <td>{{ number_format($f->importe_total, 2) }}€</td>
          <td>
            @if($f->pagado)
            <span class="badge bg-label-success">Pagado</span>
            @else
            <span class="badge bg-label-danger">Pendiente</span>
            @endif
          </td>
          <td>{{ $f->created_at->format('d/m/Y') }}</td>
          <td>
            <div class="d-flex gap-2">
              <a href="{{ route('facturas.imprimir', $f->id) }}" class="btn btn-sm btn-info" aria-label="Imprimir factura #{{ $f->id }}" target="_blank"><i class="ri-printer-line"></i></a>
              <a href="{{ route('facturas.edit', $f->id) }}" class="btn btn-sm btn-primary" aria-label="Editar factura #{{ $f->id }}">Editar</a>
              <form action="{{ route('facturas.destroy', $f->id) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar factura?')" aria-label="Eliminar factura #{{ $f->id }}">Eliminar</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center py-4" aria-live="polite">
            <i class="ri-inbox-line fs-1 text-muted"></i>
            <p class="mt-2">No se encontraron facturas</p>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($facturas->hasPages())
  <div class="card-footer">
    {{ $facturas->appends(['search' => request('search')])->links() }}
  </div>
  @endif
</div>
@endsection