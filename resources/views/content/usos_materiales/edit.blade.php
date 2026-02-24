@extends('layouts/contentNavbarLayout')

@section('title', 'Editar Consumo')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Consumo /</span> Editar Registro #{{ $uso->id }}</h4>

<div class="card">
  <div class="card-body">
    <form action="{{ route('usos_materiales.update', $uso->id) }}" method="POST">
      @csrf @method('PUT')
      <div class="row">
        {{-- Mantenemos la relación con la Orden de Trabajo --}}
        <div class="mb-3 col-md-6">
          <label class="form-label">Orden de Trabajo</label>
          <select name="encargo_id" class="form-select" required>
            @foreach($encargos as $e)
            <option value="{{ $e->id }}" {{ $uso->encargo_id == $e->id ? 'selected' : '' }}>
              #{{ $e->id }} - {{ $e->vehiculo->marca }}
            </option>
            @endforeach
          </select>
        </div>

        {{-- Posibilidad de cambiar el material si hubo un error en la selección --}}
        <div class="mb-3 col-md-6">
          <label class="form-label">Material</label>
          <select name="material_id" class="form-select" required>
            @foreach($materiales as $m)
            <option value="{{ $m->id }}" {{ $uso->material_id == $m->id ? 'selected' : '' }}>
              {{ $m->nombre }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3 col-md-4">
          <label class="form-label">Cantidad</label>
          <input type="number" step="0.01" name="cantidad" class="form-control" value="{{ $uso->cantidad }}" required>
        </div>
      </div>

      <div class="mt-4">
        <button type="submit" class="btn btn-primary me-2"><i class="ri-refresh-line me-1"></i> Actualizar</button>
        <a href="{{ route('usos_materiales.index') }}" class="btn btn-outline-secondary">Volver</a>
      </div>
    </form>
  </div>
</div>
@endsection