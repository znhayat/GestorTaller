@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('materiales.update', $material->id) }}" method="POST">
      @csrf @method('PUT')
      <div class="row">
        <div class="mb-3 col-md-6">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" value="{{ $material->nombre }}" required>
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">Tipo</label>
          <input type="text" name="tipo" class="form-control" value="{{ $material->tipo }}" required>
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
      <button type="submit" class="btn btn-primary">Actualizar</button>
      <a href="{{ route('materiales.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </form>
  </div>
</div>
@endsection