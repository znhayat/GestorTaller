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
        <div class="mb-3 col-md-6">
          <label class="form-label">Precio Unitario (€)</label>
          <input type="number" step="0.01" name="precio_unitario" class="form-control" value="{{ $material->precio_unitario }}" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Actualizar</button>
      <a href="{{ route('materiales.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </form>
  </div>
</div>
@endsection