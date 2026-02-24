@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <div class="card-header">
    <h5>Nuevo Vehículo</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('vehiculos.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        {{-- Es vital que el cliente ya exista en la base de datos para poder seleccionarlo aquí --}}
        <label class="form-label">Seleccionar Cliente (Dueño)</label>
        <select name="cliente_id" class="form-select" required>
          <option value="">-- Elige un cliente --</option>
          @foreach($clientes as $c)
          <option value="{{ $c->id }}">{{ $c->nombre }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Marca</label>
        <input type="text" name="marca" class="form-control" placeholder="Ej: BMW" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Modelo</label>
        <input type="text" name="modelo" class="form-control" placeholder="Ej: Serie 3" required>
      </div>

      <button type="submit" class="btn btn-primary">Guardar Vehículo</button>
      <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </form>
  </div>
</div>
@endsection