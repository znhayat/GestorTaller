@extends('layouts/contentNavbarLayout')

@section('content')
<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Editar Presupuesto #{{ $presupuesto->id }}</h5>
        <small class="text-muted">Orden de Trabajo: OT-{{ $presupuesto->encargo_id }}</small>
      </div>
      <div class="card-body">
        <form action="{{ route('presupuestos.update', $presupuesto->id) }}" method="POST">
          @csrf @method('PUT')

          <div class="row">
            {{-- Cuadro de resumen del vehículo para evitar errores de facturación --}}
            <div class="mb-3 col-md-12">
              <div class="alert alert-outline-primary shadow-sm" role="alert">
                <h6 class="alert-heading mb-1">Información del Vehículo</h6>
                <span>{{ $presupuesto->encargo->vehiculo->marca }} {{ $presupuesto->encargo->vehiculo->modelo }} ({{ $presupuesto->encargo->vehiculo->matricula }})</span><br>
                <small>Cliente: {{ $presupuesto->encargo->vehiculo->cliente->nombre }}</small>
              </div>
            </div>

            {{-- Uso de input-group para añadir el símbolo del Euro visualmente --}}
            <div class="mb-3 col-md-6">
              <label class="form-label">Precio Materiales (€)</label>
              <div class="input-group">
                <span class="input-group-text">€</span>
                <input type="number" step="0.01" name="precio_materiales" class="form-control" value="{{ $presupuesto->precio_materiales }}" required>
              </div>
            </div>

            <div class="mb-3 col-md-6">
              <label class="form-label">Mano de Obra (€)</label>
              <div class="input-group">
                <span class="input-group-text">€</span>
                <input type="number" step="0.01" name="precio_horas" class="form-control" value="{{ $presupuesto->precio_horas }}" required>
              </div>
            </div>

            {{-- Switch para marcar la aceptación del cliente tras la llamada telefónica --}}
            <div class="mb-3 col-md-12">
              <div class="form-check form-switch mt-3">
                <input name="aceptado" class="form-check-input" type="checkbox" id="aceptadoSwitch" {{ $presupuesto->aceptado ? 'checked' : '' }}>
                <label class="form-check-label fw-bold" for="aceptadoSwitch">Presupuesto Aceptado por el Cliente</label>
              </div>
              <p class="text-muted small">Al marcar esto, el presupuesto aparecerá como "Aceptado" en el listado principal.</p>
            </div>
          </div>

          <div class="mt-4 text-end">
            <a href="{{ route('presupuestos.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar Cambios</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection