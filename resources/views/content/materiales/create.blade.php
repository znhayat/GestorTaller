@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <div class="card-header">
    <h5>Registrar Nuevo Material</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('materiales.store') }}" method="POST">
      @csrf
      <div class="row">
        <div class="mb-3 col-md-6">
          <label class="form-label">Nombre del Material</label>
          <input type="text" name="nombre" class="form-control" placeholder="Ej: Cuero Negro" required>
        </div>

        <div class="mb-3 col-md-6">
          <label class="form-label">Categoría</label>
          <select name="tipo" class="form-select" id="tipoSelect" onchange="toggleNuevoTipo(this)" required>
            <option value="" disabled selected>Selecciona una categoría...</option>
            @foreach($categorias as $cat)
            <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
            <option value="NEW" class="text-primary fw-bold">+ Añadir nueva categoría</option>
          </select>

          {{-- Input ocult per escriure la nova categoria --}}
          <input type="text" name="nuevo_tipo" id="nuevoTipoInput" class="form-control mt-2"
            placeholder="Escribe el nombre de la nueva categoría" style="display:none;">
        </div>

        <div class="mb-3 col-md-6">
          <label class="form-label">Unidad de Medida</label>
          <select name="unidad" class="form-select" required>
            <option value="Metros">Metros</option>
            <option value="Unidades">Unidades</option>
            <option value="Litros">Litros</option>
            <option value="Rollos">Rollos</option>
          </select>
        </div>

        <div class="mb-3 col-md-6">
          <label class="form-label">Precio Unitario (€)</label>
          <input type="number" step="0.01" name="precio_unitario" class="form-control" placeholder="0.00" required>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Guardar Material</button>
      <a href="{{ route('materiales.index') }}" class="btn btn-outline-secondary">Volver</a>
    </form>
  </div>
</div>

<script>
  function toggleNuevoTipo(select) {
    const input = document.getElementById('nuevoTipoInput');
    if (select.value === 'NEW') {
      input.style.display = 'block';
      input.required = true;
      select.name = ""; // Desactivar select per no enviar "NEW"
      input.name = "tipo"; // Activar l'input per enviar el nou nom
    } else {
      input.style.display = 'none';
      input.required = false;
      select.name = "tipo"; // 
      input.name = "nuevo_tipo";
    }
  }
</script>
@endsection