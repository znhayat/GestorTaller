@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <div class="card-body">
    <h5>Crear Presupuesto</h5>
    <form action="{{ route('presupuestos.store') }}" method="POST">
      @csrf
      <div class="row">
        {{-- Selector de Orden de Trabajo: vinculamos el dinero al trabajo real --}}
        <div class="mb-3 col-md-12">
          <label class="form-label">Orden de Trabajo (Encargo)</label>
          <select name="encargo_id" class="form-select" required>
            @foreach($encargos as $e)
            <option value="{{ $e->id }}">#{{ $e->id }} - {{ $e->vehiculo->marca }} ({{ $e->vehiculo->cliente->nombre }})</option>
            @endforeach
          </select>
        </div>

        {{-- Inicializamos a 0 para que el usuario solo tenga que sobrescribir --}}
        <div class="mb-3 col-md-6">
          <label class="form-label">Precio Materiales (€)</label>
          <input type="number" step="0.01" name="precio_materiales" class="form-control" value="0">
        </div>

        <div class="mb-3 col-md-6">
          <label class="form-label">Precio Mano de Obra (€)</label>
          <input type="number" step="0.01" name="precio_horas" class="form-control" value="0">
        </div>

        <div class="mb-3 col-md-12">
          <div class="form-check form-switch mt-2">
            <input name="aceptado" class="form-check-input" type="checkbox" id="aceptado">
            <label class="form-check-label">¿El cliente ha aceptado el presupuesto?</label>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-3">Guardar Presupuesto</button>
    </form>
  </div>
</div>
@endsection