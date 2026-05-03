@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <div class="card-header">
    <h5>Editar Material: {{ $material->nombre }}</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('materiales.update', $material->id) }}" method="POST">
      @csrf @method('PUT')
      <div class="row">
        <div class="mb-3 col-md-6">
          <label class="form-label">Nombre del Material</label>
          <input type="text" name="nombre" class="form-control" value="{{ $material->nombre }}" required>
        </div>

        <div class="mb-3 col-md-6">
          <label class="form-label">Categoría</label>
          <select name="tipo" class="form-select" id="tipoSelect" onchange="toggleNuevoTipo(this)" required>
            @foreach($categorias as $cat)
            <option value="{{ $cat }}" {{ $material->tipo == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
            <option value="NEW" class="text-primary fw-bold {{ !in_array($material->tipo, $categorias) ? 'selected' : '' }}">+ Añadir nueva categoría</option>
          </select>
          {{-- Input oculto para nueva categoría --}}
          <input type="text" name="{{ !in_array($material->tipo, $categorias) ? 'tipo' : 'nuevo_tipo' }}" id="nuevoTipoInput" 
                 class="form-control mt-2" placeholder="Escribe el nombre de la nueva categoría" 
                 style="{{ !in_array($material->tipo, $categorias) ? 'display:block;' : 'display:none;' }}"
                 value="{{ !in_array($material->tipo, $categorias) ? $material->tipo : '' }}">
        </div>

        <div class="mb-3 col-md-4">
          <label class="form-label">Unidad de Medida</label>
          <select name="unidad" class="form-select" required>
            <option value="Metros" {{ $material->unidad == 'Metros' ? 'selected' : '' }}>Metros</option>
            <option value="Unidades" {{ $material->unidad == 'Unidades' ? 'selected' : '' }}>Unidades</option>
            <option value="Litros" {{ $material->unidad == 'Litros' ? 'selected' : '' }}>Litros</option>
            <option value="Rollos" {{ $material->unidad == 'Rollos' ? 'selected' : '' }}>Rollos</option>
            <option value="Botes" {{ $material->unidad == 'Botes' ? 'selected' : '' }}>Botes</option>
          </select>
        </div>

        <div class="mb-3 col-md-4">
          <label class="form-label">Precio Unitario (€)</label>
          <div class="input-group">
            <input type="number" step="0.01" name="precio_unitario" class="form-control" value="{{ $material->precio_unitario }}" required>
            <span class="input-group-text">€</span>
          </div>
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Stock Actual</label>
            <input type="number" step="0.01" name="stock" class="form-control" value="{{ (float)$material->stock }}">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label text-warning fw-bold">Stock Mínimo (Alerta)</label>
            <input type="number" step="0.01" name="stock_minimo" class="form-control" value="{{ (float)$material->stock_minimo }}">
        </div>

        <div class="mb-3 col-md-12">
            <label class="form-label">Descripción / Notas</label>
            <textarea name="descripcion" class="form-control" rows="2">{{ $material->descripcion }}</textarea>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Actualizar Material</button>
      <a href="{{ route('materiales.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </form>
  </div>
</div>

<script>
  function toggleNuevoTipo(select) {
    const input = document.getElementById('nuevoTipoInput');
    if (select.value === 'NEW') {
      input.style.display = 'block';
      input.required = true;
      select.name = ""; 
      input.name = "tipo"; 
    } else {
      input.style.display = 'none';
      input.required = false;
      select.name = "tipo"; 
      input.name = "nuevo_tipo";
    }
  }
</script>
@endsection