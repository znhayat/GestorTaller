@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <div class="card-header">
    <h5>Abrir Nueva Orden de Trabajo</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('encargos.store') }}" method="POST" enctype="multipart/form-data">
      @csrf {{-- Token de seguridad para proteger el formulario --}}

      <div class="mb-3">
        <label class="form-label">Vehículo</label>
        <select name="vehiculo_id" class="form-select" required>
          <option value="">-- Selecciona el coche --</option>
          @foreach($vehiculos as $v)
          <option value="{{ $v->id }}">{{ $v->marca }} {{ $v->modelo }} ({{ $v->cliente->nombre }})</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Descripción del problema</label>
        {{-- El área donde el mecánico o recepcionista anota el fallo reportado --}}
        <textarea name="descripcion" class="form-control" rows="3" placeholder="¿Qué le pasa al coche?" required></textarea>
      </div>

      <div class="row">
        {{-- Campo de fecha: por defecto toma la fecha de hoy para agilizar el registro --}}
        <div class="col-md-6 mb-3">
          <label class="form-label">Fecha de Entrada</label>
          <input type="date" name="fecha_entrada" class="form-control" value="{{ date('Y-m-d') }}">
        </div>

        {{-- Selector de estado: permite clasificar la urgencia o fase inicial del trabajo --}}
        <div class="col-md-6 mb-3">
          <label class="form-label">Estado Inicial</label>
          <select name="estado" class="form-select">
            <option value="Pendiente">Pendiente</option>
            <option value="En Proceso">En Proceso</option>
            <option value="Esperando Piezas">Esperando Piezas</option>
          </select>
        </div>
      </div>

      <div class="mb-4">
        <label class="form-label">Subir Fotografías Iniciales (Opcional)</label>
        <input class="form-control" type="file" name="fotos[]" multiple accept="image/*">
        <div class="form-text">Puedes subir varias fotos del estado actual del vehículo para adjuntarlas al encargo.</div>
      </div>

      {{-- Acciones finales: Guardar la orden o volver al listado --}}
      <button type="submit" class="btn btn-primary">Crear Encargo</button>
      <a href="{{ route('encargos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </form>
  </div>
</div>
@endsection