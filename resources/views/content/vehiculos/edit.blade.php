@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <div class="card-header">
    <h5>Editar Vehículo</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('vehiculos.update', $vehiculo->id) }}" method="POST">
      @csrf @method('PUT')
      <div class="mb-3">
        <label class="form-label">Dueño</label>
        <select name="cliente_id" class="form-select" required>
          @foreach($clientes as $c)
          <option value="{{ $c->id }}" {{ $vehiculo->cliente_id == $c->id ? 'selected' : '' }}>
            {{ $c->nombre }}
          </option>
          @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Marca</label>
        <input type="text" name="marca" class="form-control" value="{{ $vehiculo->marca }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Modelo</label>
        <input type="text" name="modelo" class="form-control" value="{{ $vehiculo->modelo }}" required>
      </div>
      <button type="submit" class="btn btn-primary">Actualizar</button>
      <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </form>
  </div>
</div>
@endsection