@extends('layouts/contentNavbarLayout')

@section('title', 'Historial de Clientes')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
      <h4 class="fw-bold mb-1"><i class="ri-history-line me-2 text-primary"></i>Historial de Clientes</h4>
      <p class="text-muted mb-0">Listado de clientes con intervenciones en el taller</p>
    </div>
    <div class="d-flex gap-2">
      <button type="button" class="btn btn-outline-success" onclick="exportTableToExcel('historial-table', 'historial_clientes')">
        <i class="ri-file-excel-2-line me-1"></i> Exportar
      </button>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3 py-3">
      <form method="GET" action="{{ route('encargos.historial') }}" class="d-flex position-relative" style="min-width:300px;">
        <i class="ri-search-line position-absolute ms-3 text-muted" style="top:50%;transform:translateY(-50%);"></i>
        <input type="text" name="search" class="form-control ps-5" placeholder="Buscar cliente, marca o matrícula..." value="{{ request('search') }}">
      </form>
      <div class="d-flex gap-2 align-items-center">
        <span class="badge bg-label-primary fs-6">{{ $clientes->total() }} clientes</span>
      </div>
    </div>

    <div class="table-responsive text-nowrap">
      <table class="table table-hover align-middle mb-0" id="historial-table">
        <thead class="table-light">
          <tr>
            <th class="ps-4">Nombre del Cliente</th>
            <th>Contacto</th>
            <th>Último Vehículo</th>
            <th class="text-center">Total Trabajos</th>
            <th>Última Visita</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($clientes as $c)
          <tr>
            {{-- Columna Cliente (Igual que en tabla Usuarios) --}}
            <td class="ps-4">
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm bg-label-primary me-3">
                  <span class="avatar-initial rounded-circle fw-bold">{{ strtoupper(substr($c->nombre, 0, 1)) }}</span>
                </div>
                <div>
                  <h6 class="mb-0 fw-bold text-dark">{{ $c->nombre }} {{ $c->apellido }}</h6>
                  <small class="text-muted">Cliente #{{ $c->id }}</small>
                </div>
              </div>
            </td>

            {{-- Columna Contacto --}}
            <td>
              <div class="d-flex flex-column">
                <span class="fw-bold text-dark">{{ $c->telefono }}</span>
                <small class="text-muted">{{ $c->correo ?? 'Sin email' }}</small>
              </div>
            </td>

            {{-- Columna Vehículo --}}
            <td>
              @php
                $ultimoV = $c->vehiculos->sortByDesc(function($v) {
                    return $v->encargos->max('updated_at');
                })->first();
              @endphp
              @if($ultimoV)
                <span class="badge bg-label-dark">{{ $ultimoV->marca }} {{ $ultimoV->modelo }}</span>
                <div class="small text-muted mt-1">{{ $ultimoV->matricula ?? 'S/M' }}</div>
              @else
                <span class="text-muted small">—</span>
              @endif
            </td>

            {{-- Columna Trabajos --}}
            <td class="text-center">
              <span class="badge bg-label-info rounded-pill px-3 fw-bold">
                {{ $c->vehiculos->flatMap->encargos->count() }}
              </span>
            </td>

            {{-- Columna Fecha --}}
            <td>
              @php
                $ultimoE = $c->vehiculos->flatMap->encargos->sortByDesc('updated_at')->first();
              @endphp
              @if($ultimoE)
                <div class="fw-bold text-dark">{{ $ultimoE->updated_at->format('d/m/Y') }}</div>
                <small class="text-muted">Finalizado</small>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>

            {{-- Acciones (Igual que en todas las tablas) --}}
            <td class="text-center">
              <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('clientes.show', $c->id) }}" class="btn btn-sm btn-primary">
                  <i class="ri-folder-user-line me-1"></i> Ver Ficha
                </a>
                
                @php
                  $uF = $c->vehiculos->flatMap->encargos->whereNotNull('factura')->sortByDesc('updated_at')->first()?->factura;
                @endphp
                @if($uF)
                <a href="{{ route('facturas.imprimir', $uF->id) }}" class="btn btn-sm btn-outline-info" target="_blank">
                  <i class="ri-printer-line me-1"></i> Imprimir
                </a>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-5 text-muted">
              <i class="ri-file-search-line fs-1 d-block mb-3"></i>
              No hay clientes registrados en el historial
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($clientes->hasPages())
    <div class="card-footer border-top bg-white py-3">
      {{ $clientes->appends(['search' => request('search')])->links() }}
    </div>
    @endif
  </div>
</div>

<script>
function exportTableToExcel(tableID, filename = ''){
    let downloadLink;
    const dataType = 'application/vnd.ms-excel';
    const tableSelect = document.getElementById(tableID);
    const tableClone = tableSelect.cloneNode(true);
    const rows = tableClone.querySelectorAll('tr');
    rows.forEach(row => { if(row.lastElementChild) row.removeChild(row.lastElementChild); });
    const tableHTML = tableClone.outerHTML.replace(/ /g, '%20');
    filename = filename ? filename + '.xls' : 'historial_clientes.xls';
    downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    if(navigator.msSaveOrOpenBlob){
        const blob = new Blob(['\ufeff', tableHTML], { type: dataType });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}
</script>
@endsection
