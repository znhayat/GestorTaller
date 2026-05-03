@extends('layouts/contentNavbarLayout')

@section('content')
<style>
    .search-container { min-width: 250px; }
    .search-icon { top: 50%; transform: translateY(-50%); left: 15px; color: #a1acb8; }
    .clear-search { top: 50%; transform: translateY(-50%); right: 10px; color: #a1acb8; cursor: pointer; }
</style>

<div class="card">
  <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <h5 class="mb-0">Vehículos en Taller</h5>
    <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
      <!-- Buscador -->
      <form method="GET" action="{{ route('vehiculos.index') }}" class="d-flex position-relative me-2 search-container">
        <input type="text" name="search" class="form-control ps-5 pe-4 w-100" placeholder="Buscar vehículo..." value="{{ request('search') }}">
        <i class="ri-search-line position-absolute search-icon"></i>
        @if(request('search'))
        <a href="{{ route('vehiculos.index') }}" class="position-absolute clear-search" title="Limpiar búsqueda">
          <i class="ri-close-circle-line"></i>
        </a>
        @endif
      </form>

      <button type="button" class="btn btn-outline-success" onclick="exportTableToExcel('vehiculos-table', 'vehiculos_zana')">
        <i class="ri-file-excel-2-line me-1"></i> Exportar
      </button>
      <a href="{{ route('vehiculos.create') }}" class="btn btn-primary"><i class="ri-add-line me-1"></i> Registrar Vehículo</a>
    </div>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table table-hover" id="vehiculos-table">
      <thead>
        <tr>
          <th>Código</th>
          <th>Dueño</th>
          <th>Marca</th>
          <th>Modelo</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($vehiculos as $v)
        <tr>
          <td><strong class="text-primary">#{{ $v->id }}</strong></td>
          <td><strong>{{ $v->cliente->nombre }}</strong></td>
          <td>{{ $v->marca }}</td>
          <td>{{ $v->modelo }}</td>
          <td>
            <div class="d-flex justify-content-center gap-2">
              <a href="{{ route('vehiculos.edit', $v->id) }}" class="btn btn-sm btn-primary">
                <i class="ri-pencil-line me-1"></i> Editar
              </a>

              <form action="{{ route('vehiculos.destroy', $v->id) }}" method="POST" onsubmit="return confirm('¿Borrar vehículo?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                  <i class="ri-delete-bin-line me-1"></i> Eliminar
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @if($vehiculos->hasPages())
  <div class="card-footer d-flex justify-content-center">
    {{ $vehiculos->appends(['search' => request('search')])->links() }}
  </div>
  @endif
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
    filename = filename ? filename + '.xls' : 'excel_data.xls';
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