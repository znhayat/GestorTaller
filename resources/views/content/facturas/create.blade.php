@extends('layouts/contentNavbarLayout')

@section('title', 'Generar Factura')

@section('content')
<div class="card">
  <div class="card-body">
    <h4 style="font-family: 'Montserrat', sans-serif;">Nueva Factura</h4>

    <form action="{{ route('facturas.store') }}" method="POST">
      @csrf
      <div class="row">
        {{-- Selección del encargo, vinculamos la factura a un trabajo específico --}}
        <div class="mb-3 col-md-6">
          <label class="form-label">Encargo Asociado</label>
          <select name="encargo_id" class="form-select" required>
            <option value="">Selecciona el trabajo...</option>
            @foreach($encargos as $e)
            {{-- Mostramos ID, nombre del cliente y marca del coche para evitar errores --}}
            <option value="{{ $e->id }}">
              #{{ $e->id }} - {{ $e->vehiculo->cliente->nombre }} ({{ $e->vehiculo->marca }})
            </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3 col-md-6">
          <label class="form-label">Importe Total</label>
          <input type="number" step="0.01" name="importe_total" class="form-control" placeholder="0.00" required>
        </div>

        {{-- Opción rápida para marcar si el cliente paga en el mismo momento de la entrega --}}
        <div class="mb-3 col-md-4">
          <div class="form-check mt-3">
            <input name="pagado" class="form-check-input" type="checkbox" id="pagado">
            <label class="form-check-label" for="pagado">¿Pagado ahora?</label>
          </div>
        </div>

        <div class="mb-3 col-md-4">
          <label class="form-label">Fecha de Pago (Opcional)</label>
          <input type="date" name="fecha_pago" class="form-control">
        </div>
      </div>

      <button type="submit" class="btn btn-primary mt-2">Crear Factura</button>
    </form>
  </div>
</div>
@endsection