@extends('layouts/contentNavbarLayout')

@section('title', 'Nuevo Cliente')

@section('content')
<div class="card">
  <div class="card-header">
    <h5>Registrar Cliente</h5>
  </div>
  <div class="card-body">
    {{-- El formulario apunta al método 'store' para insertar el nuevo registro --}}
    <form action="{{ route('clientes.store') }}" method="POST">
      {{-- Token de seguridad obligatorio en Laravel para evitar ataques externos --}}
      @csrf

      <div class="mb-3">
        <label class="form-label">Nombre</label>
        {{-- Usamos placeholder para dar una pista de qué escribir --}}
        <input type="text" name="nombre" class="form-control" placeholder="Juan" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Apellido</label>
        {{-- Usamos placeholder para dar una pista de qué escribir --}}
        <input type="text" name="apellido" class="form-control" placeholder="Pérez" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Teléfono</label>
        {{-- Campo requerido para asegurar que siempre tengamos cómo contactar al dueño --}}
        <input type="text" name="telefono" class="form-control" placeholder="600000000" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Correo</label>
        {{-- Campo requerido para asegurar que siempre tengamos cómo contactar al dueño --}}
        <input type="email" name="correo" class="form-control" placeholder="juan.perez@example.com" required>
      </div>

      {{-- Botonera: Guardar envía el formulario, Cancelar nos devuelve al listado --}}
      <button type="submit" class="btn btn-primary">Guardar</button>
      <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </form>
  </div>
</div>
@endsection