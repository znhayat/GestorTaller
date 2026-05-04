@extends('layouts/contentNavbarLayout')

@section('content')
<style>
    .search-container { min-width: 250px; }
    .search-icon { top: 50%; transform: translateY(-50%); left: 15px; color: #a1acb8; }
    .clear-search { top: 50%; transform: translateY(-50%); right: 10px; color: #a1acb8; cursor: pointer; }
</style>

<div class="card">
  <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <h5 class="mb-0">Gestión de Clientes</h5>
    <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
      <!-- Buscador -->
      <form method="GET" action="{{ route('clientes.index') }}" class="d-flex position-relative me-2 search-container">
        <input type="text" name="search" class="form-control ps-5 pe-4 w-100" placeholder="Buscar cliente..." value="{{ request('search') }}">
        <i class="ri-search-line position-absolute search-icon"></i>
        @if(request('search'))
        <a href="{{ route('clientes.index') }}" class="position-absolute clear-search" title="Limpiar búsqueda">
          <i class="ri-close-circle-line"></i>
        </a>
        @endif
      </form>

      <button type="button" class="btn btn-outline-success" onclick="exportTableToExcel('clientes-table', 'clientes_zana')">
        <i class="ri-file-excel-2-line me-1"></i> Exportar
      </button>
      <a href="{{ route('clientes.create') }}" class="btn btn-primary"><i class="ri-user-add-line me-1"></i> Nuevo Cliente</a>
    </div>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table table-hover" id="clientes-table">
      <thead>
        <tr>
          <th>Código</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Teléfono</th>
          <th>Correo</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($clientes as $c)
        <tr>
          <td><strong class="text-primary">#{{ $c->id }}</strong></td>
          <td>
            <a href="{{ route('clientes.show', $c->id) }}" class="fw-bold text-dark h6 mb-0">{{ $c->nombre }}</a>
          </td>
          <td>{{ $c->apellido }}</td>
          <td>{{ $c->telefono }}</td>
          <td>{{ $c->correo }}</td>
          <td>
            <div class="d-flex justify-content-center gap-1">
              <a href="{{ route('clientes.show', $c->id) }}" class="btn btn-sm btn-info" title="Ver Ficha">
                <i class="ri-eye-line me-1"></i> Ver
              </a>
              <a href="{{ route('clientes.edit', $c->id) }}" class="btn btn-sm btn-primary" title="Editar">
                <i class="ri-pencil-line me-1"></i> Editar
              </a>

              @if(auth()->user()->role === 'admin')
              <form action="{{ route('clientes.destroy', $c->id) }}" method="POST" onsubmit="return confirm('¿Eliminar cliente del sistema?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                  <i class="ri-delete-bin-line me-1"></i> Eliminar
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @if($clientes->hasPages())
  <div class="card-footer d-flex justify-content-center">
    {{ $clientes->appends(['search' => request('search')])->links() }}
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
    filename = filename ? filename + '.xls' : 'clientes_zana.xls';
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