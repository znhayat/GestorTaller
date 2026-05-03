@extends('layouts/contentNavbarLayout')

@section('title', 'Gestión de Facturas')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Historial Económico</h4>
    <div class="d-flex gap-2">
      <a href="{{ route('facturas.create') }}" class="btn btn-primary">
        <i class="ri-add-line me-1"></i> Generar Factura
      </a>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <!-- Buscador y Exportación -->
    <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3 py-3">
      <div class="d-flex align-items-center position-relative" style="min-width: 300px;">
        <i class="ri-search-line position-absolute ms-3 text-muted"></i>
        <form method="GET" action="{{ route('facturas.index') }}" class="w-100">
          <input type="text" name="search" class="form-control ps-5" placeholder="Buscar por cliente, matrícula o importe..." value="{{ request('search') }}">
        </form>
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-success btn-export-csv" data-filename="facturas_zana">
          <i class="ri-file-excel-line me-1"></i> Exportar Excel
        </button>
      </div>
    </div>

    <!-- Tabla de Resultados -->
    <div class="table-responsive text-nowrap">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th class="fw-bold">ID</th>
            <th class="fw-bold">Cliente / Vehículo</th>
            <th class="fw-bold">Total</th>
            <th class="fw-bold text-center">Estado</th>
            <th class="fw-bold">Fecha Emisión</th>
            <th class="fw-bold text-center">Operaciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($facturas as $f)
          <tr>
            <td><span class="text-primary fw-bold">#{{ $f->id }}</span></td>
            <td>
              <div class="d-flex flex-column">
                <span class="fw-bold text-dark">{{ $f->encargo->vehiculo->cliente->nombre }} {{ $f->encargo->vehiculo->cliente->apellido }}</span>
                <small class="text-muted">{{ $f->encargo->vehiculo->marca }} {{ $f->encargo->vehiculo->modelo }}</small>
              </div>
            </td>
            <td><span class="fw-bold text-dark fs-6">{{ number_format($f->importe_total, 2) }} €</span></td>
            <td class="text-center">
              @if($f->pagado)
                <span class="badge bg-success text-white px-3" style="min-width: 90px;">PAGADO</span>
              @else
                <span class="badge bg-danger text-white px-3" style="min-width: 90px;">PENDIENTE</span>
              @endif
            </td>
            <td>{{ $f->created_at->format('d/m/Y') }}</td>
            <td>
              <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('facturas.imprimir', $f->id) }}" class="btn btn-sm btn-info fw-bold" target="_blank">
                  <i class="ri-printer-line me-1"></i> IMPRIMIR
                </a>
                <a href="{{ route('facturas.edit', $f->id) }}" class="btn btn-sm btn-primary fw-bold">
                  <i class="ri-edit-line me-1"></i> EDITAR
                </a>
                <form action="{{ route('facturas.destroy', $f->id) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres borrar esta factura?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center" title="Eliminar registro">
                    <i class="ri-delete-bin-line me-1"></i> Eliminar
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-5">
              <i class="ri-file-search-line fs-1 text-muted d-block mb-3"></i>
              <span class="text-muted">No se han encontrado registros contables</span>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    @if($facturas->hasPages())
    <div class="card-footer border-top bg-white py-3">
      {{ $facturas->appends(['search' => request('search')])->links() }}
    </div>
    @endif
  </div>
</div>
@endsection