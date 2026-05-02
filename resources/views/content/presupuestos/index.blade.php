@extends('layouts/contentNavbarLayout')

@section('content')
<style>
    .search-container { min-width: 250px; }
    .search-icon { top: 50%; transform: translateY(-50%); left: 15px; color: #a1acb8; }
    .clear-search { top: 50%; transform: translateY(-50%); right: 10px; color: #a1acb8; cursor: pointer; }
</style>

<div class="card">
  <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <h5 class="mb-0">Presupuestos Enviados</h5>
    <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
      <!-- Buscador -->
      <form method="GET" action="{{ route('presupuestos.index') }}" class="d-flex position-relative me-2 search-container">
        <label for="search-presupuestos" class="visually-hidden">Buscar presupuestos</label>
        <input type="text" id="search-presupuestos" name="search" class="form-control ps-5 pe-4 w-100" placeholder="Buscar por cliente..." value="{{ request('search') }}">
        <i class="ri-search-line position-absolute search-icon" aria-hidden="true"></i>
        @if(request('search'))
        <a href="{{ route('presupuestos.index') }}" class="position-absolute clear-search" title="Limpiar búsqueda" aria-label="Limpiar búsqueda">
          <i class="ri-close-circle-line" aria-hidden="true"></i>
        </a>
        @endif
      </form>

      <button type="button" class="btn btn-outline-success" onclick="exportTableToExcel('presupuestos-table', 'presupuestos_zana')">
        <i class="ri-file-excel-2-line me-1"></i> Exportar
      </button>
      <a href="{{ route('presupuestos.create') }}" class="btn btn-primary"><i class="ri-add-line me-1"></i> Nuevo Presupuesto</a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover" id="presupuestos-table" aria-label="Listado de presupuestos">
      <thead>
        <tr>
          <th>Encargo</th>
          <th>Materiales</th>
          <th>Mano de Obra</th>
          <th>Total</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($presupuestos as $p)
        <tr>
          <td>
            <strong>#{{ $p->encargo_id }}</strong> - {{ $p->encargo->vehiculo->cliente->nombre }}
          </td>
          <td>{{ number_format($p->precio_materiales, 2) }}€</td>
          <td>{{ number_format($p->precio_horas, 2) }}€</td>
          <td><strong>{{ number_format($p->total, 2) }}€</strong></td>
          <td>
            @if($p->aceptado)
            <span class="badge bg-label-success">Aceptado</span>
            @else
            <span class="badge bg-label-warning">Pendiente</span>
            @endif
          </td>
          <td>
            <div class="d-flex gap-2">
              <a href="{{ route('presupuestos.edit', $p->id) }}" class="btn btn-sm btn-primary">Editar</a>
              <form action="{{ route('presupuestos.destroy', $p->id) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar?')">Borrar</button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="card-footer d-flex justify-content-center">
    {{ $presupuestos->appends(['search' => request('search')])->links() }}
  </div>
</div>

<script>
function exportTableToExcel(tableID, filename = ''){
    let downloadLink;
    const dataType = 'application/vnd.ms-excel';
    const tableSelect = document.getElementById(tableID);
    
    // Clonamos la tabla para no afectar la vista
    const tableClone = tableSelect.cloneNode(true);
    // Eliminamos la última columna (Acciones) de cada fila del clon para que no salga en el Excel
    const rows = tableClone.querySelectorAll('tr');
    rows.forEach(row => {
        if(row.lastElementChild) row.removeChild(row.lastElementChild);
    });

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