@extends('layouts/contentNavbarLayout')

@section('title', 'Editar Cita')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Citas /</span> Editar Cita</h4>

<div class="card">
  <div class="card-body">
    {{-- Formulario que apunta al método 'update'. Importante usar @method('PUT') porque los navegadores no lo soportan de forma nativa --}}
    <form action="{{ route('citas.update', $cita->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Trabajo / Encargo</label>
        {{-- Desplegable para cambiar el encargo si nos equivocamos al asignarlo --}}
        <select name="encargo_id" class="form-select" required>
          @foreach($encargos as $e)
          {{-- Comprobamos cuál es el encargo actual para dejarlo seleccionado por defecto --}}
          <option value="{{ $e->id }}" {{ $cita->encargo_id == $e->id ? 'selected' : '' }}>
            {{ $e->vehiculo->marca }} {{ $e->vehiculo->modelo }} - {{ $e->vehiculo->cliente->nombre }}
          </option>
          @endforeach
        </select>
      </div>

      <div class="row">
        {{-- Campo de fecha: recuperamos el valor que ya tenía la cita en la base de datos --}}
        <div class="mb-3 col-md-6">
          <label class="form-label">Fecha</label>
          <input type="date" name="fecha" class="form-control" value="{{ $cita->fecha }}" required>
        </div>

        {{-- Campo de hora: lo mismo, se rellena automáticamente con la hora guardada --}}
        <div class="mb-3 col-md-6">
          <label class="form-label">Hora</label>
          <input type="time" name="hora" class="form-control" value="{{ $cita->hora }}" required>
        </div>
      </div>

      <div class="mt-3">
        {{-- Botón de envío con icono de guardado --}}
        <button type="submit" class="btn btn-primary me-2">
          <i class="ri-save-line me-1"></i> Actualizar Cita
        </button>
        {{-- Botón de cancelar para volver al listado sin cambiar nada --}}
        <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>
@endsection