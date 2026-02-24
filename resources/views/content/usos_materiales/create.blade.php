@extends('layouts/contentNavbarLayout')

@section('title', 'Registrar Consumo')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Consumo /</span> Nuevo Registro</h4>

<div class="card">
  <div class="card-body">
    <form action="{{ route('usos_materiales.store') }}" method="POST">
      @csrf
      <div class="row">
        {{-- Selección de la OT activa --}}
        <div class="mb-3 col-md-6">
          <label class="form-label">Orden de Trabajo (Encargo)</label>
          <select name="encargo_id" class="form-select" required>
            <option value="">Selecciona el encargo...</option>
            @foreach($encargos as $e)
            <option value="{{ $e->id }}">#{{ $e->id }} - {{ $e->vehiculo->marca }} ({{ $e->vehiculo->cliente->nombre }})</option>
            @endforeach
          </select>
        </div>

        {{-- Aquí se muestra el precio y unidad para que el usuario sepa qué está descontando --}}
        <div class="mb-3 col-md-6">
          <label class="form-label">Material del Catálogo</label>
          <select name="material_id" class="form-select" required>
            <option value="">Selecciona material...</option>
            @foreach($materiales as $m)
            <option value="{{ $m->id }}">{{ $m->nombre }} ({{ $m->precio_unitario }}€/{{ $m->unidad }})</option>
            @endforeach
          </select>
        </div>

        {{-- Cantidad: admite decimales para medidas como metros o litros --}}
        <div class="mb-3 col-md-4">
          <label class="form-label">Cantidad Utilizada</label>
          <input type="number" step="0.01" name="cantidad" class="form-control" placeholder="0.00" required>
        </div>
      </div>

      <div class="mt-4">
        <button type="submit" class="btn btn-primary me-2"><i class="ri-save-line me-1"></i> Guardar Registro</button>
        <a href="{{ route('usos_materiales.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>
@endsection