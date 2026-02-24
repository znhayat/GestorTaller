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
          <label class="form-label">Tipo</label>
          <input type="text" name="tipo" class="form-control" placeholder="Ej: Tela, Espuma, Pegamento" required>
        </div>
        {{-- Selector de unidad de medida para estandarizar el inventario --}}
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
@endsection