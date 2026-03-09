@extends('layouts/contentNavbarLayout')

@section('title', 'Editar Cliente')

@section('content')
<div class="card">
  <div class="card-header">
    <h5>Editar Cliente</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
      @csrf @method('PUT')
      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" value="{{ $cliente->nombre }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Apellido</label>
        <input type="text" name="apellido" class="form-control" value="{{ $cliente->apellido }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control" value="{{ $cliente->telefono }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">correo</label>
        <input type="email" name="correo" class="form-control" value="{{ $cliente->correo }}" required>
      </div>
      <button type="submit" class="btn btn-primary">Actualizar</button>
      <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </form>
  </div>
</div>
@endsection