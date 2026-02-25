@extends('layouts/contentNavbarLayout')

@section('title', 'Programar Cita')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Citas /</span> Nueva Cita</h4>

<div class="card">
  <div class="card-header">
    <h5>Programar Nueva Cita</h5>
  </div>
  <div class="card-body">
    {{-- Formulario de creación: apunta al método 'store' del controlador --}}
    <form action="{{ route('citas.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label class="form-label">Seleccionar Trabajo (Encargo)</label>
        {{-- Listado de encargos: mostramos coche, cliente y una breve descripción del problema --}}
        <select name="encargo_id" class="form-select" required>
          <option value="">-- Selecciona el vehículo y cliente --</option>
          @foreach($encargos as $e)
          <option value="{{ $e->id }}">
            {{ $e->vehiculo->marca }} {{ $e->vehiculo->modelo }} - {{ $e->vehiculo->cliente->nombre }}
            ({{ Str::limit($e->descripcion, 30) }})
          </option>
          @endforeach
        </select>
      </div>

      <div class="row">
        {{-- Campo de fecha: por defecto he puesto la fecha del día actual usando date() --}}
        <div class="mb-3 col-md-6">
          <label class="form-label">Fecha</label>
          <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        {{-- Campo de hora: HTML5 nos facilita el selector de tiempo --}}
        <div class="mb-3 col-md-6">
          <label class="form-label">Hora</label>
          <input type="time" name="hora" class="form-control" required>
        </div>
      </div>

      <div class="mt-3">
        {{-- Botón principal con icono de calendario para hacerlo más visual --}}
        <button type="submit" class="btn btn-primary me-2">
          <i class="ri-calendar-check-line me-1"></i> Confirmar Cita
        </button>
        {{-- Enlace para volver atrás si el usuario se arrepiente --}}
        <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>
@endsection