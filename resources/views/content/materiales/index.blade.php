@extends('layouts/contentNavbarLayout')

@section('content')
<style>
    .search-container { min-width: 300px; }
    .search-icon { top: 50%; transform: translateY(-50%); left: 15px; color: #a1acb8; }
</style>

<div class="container-xxl">
  <div class="d-flex justify-content-between align-items-center py-4 flex-wrap gap-3">
    <div class="d-flex align-items-center">
        <a href="{{ route('materiales.index') }}" class="btn btn-outline-secondary me-3 d-flex align-items-center">
            <i class="ri-arrow-left-s-line me-1"></i> Volver
        </a>
        <div>
            <h4 class="fw-bold mb-0">Inventario: {{ $tipo ?: 'Búsqueda' }}</h4>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('materiales.index') }}">Materiales</a></li>
                <li class="breadcrumb-item active">{{ $tipo ?: 'Resultados' }}</li>
              </ol>
            </nav>
        </div>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
      <form method="GET" action="{{ route('materiales.index') }}" class="d-flex position-relative search-container">
        <input type="hidden" name="tipo" value="{{ $tipo }}">
        <input type="text" name="search" class="form-control ps-5 pe-4 w-100" placeholder="Buscar en esta categoría..." value="{{ request('search') }}">
        <i class="ri-search-line position-absolute search-icon"></i>
      </form>
      <button type="button" class="btn btn-outline-success" onclick="exportTableToExcel('materiales-table', 'inventario_{{ Str::slug($tipo) }}')">
        <i class="ri-file-excel-2-line me-1"></i> Exportar
      </button>
      <a href="{{ route('materiales.create') }}" class="btn btn-primary"><i class="ri-add-line me-1"></i> Nuevo</a>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0" id="materiales-table">
        <thead class="table-light">
          <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Unidad</th>
            <th>Precio</th>
            <th>Stock Actual</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($materiales as $m)
          <tr>
            <td><strong class="text-primary">#{{ $m->id }}</strong></td>
            <td>
              <div class="d-flex flex-column">
                <strong class="text-dark">{{ $m->nombre }}</strong>
                @if($m->descripcion)
                <small class="text-muted text-truncate" style="max-width: 250px;" title="{{ $m->descripcion }}">
                  {{ $m->descripcion }}
                </small>
                @endif
              </div>
            </td>
            <td><span class="badge bg-label-primary">{{ $m->tipo ?? 'General' }}</span></td>
            <td>{{ $m->unidad }}</td>
            <td class="fw-bold">{{ number_format($m->precio_unitario, 2) }}€</td>
            <td>
              @php
                $sClass = 'bg-label-success';
                if($m->stock <= 0) $sClass = 'bg-label-danger';
                elseif($m->stock <= $m->stock_minimo) $sClass = 'bg-label-warning';
              @endphp
              <div class="d-flex align-items-center">
                <span class="badge {{ $sClass }} p-2 me-2" style="min-width: 60px;">{{ (float)$m->stock }}</span>
                <small class="text-muted" style="font-size: 0.75rem;">Mín: {{ (float)$m->stock_minimo }}</small>
              </div>
            </td>
            <td class="text-center">
              <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('materiales.edit', $m->id) }}" class="btn btn-sm btn-primary">
                  <i class="ri-edit-line me-1"></i> Editar
                </a>
                <form action="{{ route('materiales.destroy', $m->id) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="ri-delete-bin-line me-1"></i> Borrar
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">
                No hay productos en esta categoría
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($materiales->hasPages())
    <div class="card-footer d-flex justify-content-center">
        {{ $materiales->appends(request()->all())->links() }}
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
    filename = filename ? filename + '.xls' : 'inventario.xls';
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