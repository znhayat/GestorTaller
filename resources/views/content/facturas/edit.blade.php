@extends('layouts/contentNavbarLayout')

@section('title', 'Editar Factura')

@section('content')
<div class="card">
  <div class="card-body">
    <h4 style="font-family: 'Montserrat', sans-serif;">Editar Factura #{{ $factura->id }}</h4>

    <form action="{{ route('facturas.update', $factura->id) }}" method="POST">
      @csrf @method('PUT') {{-- PUT es necesario para actualizar registros existentes --}}

      <div class="row">
        {{-- Input numérico con step 0.01 para permitir decimales (céntimos) --}}
        <div class="mb-3 col-md-6">
          <label class="form-label">Importe Total (€)</label>
          <input type="number" step="0.01" name="importe_total" class="form-control" value="{{ $factura->importe_total }}" required>
        </div>

        {{-- Switch visual para cambiar el estado de pago de forma rápida --}}
        <div class="mb-3 col-md-6 text-center">
          <label class="form-label d-block">Estado de Pago</label>
          <div class="form-check form-switch d-inline-block">
            {{-- Si la factura ya está pagada en BD, el switch aparecerá encendido --}}
            <input name="pagado" class="form-check-input" type="checkbox" {{ $factura->pagado ? 'checked' : '' }}>
            <label class="form-check-label">Marcado como Pagado</label>
          </div>
        </div>

        {{-- Fecha de pago: útil para la contabilidad y saber cuándo entró el dinero --}}
        <div class="mb-3 col-md-6">
          <label class="form-label">Fecha de Pago</label>
          <input type="date" name="fecha_pago" class="form-control" value="{{ $factura->fecha_pago }}">
        </div>
      </div>

      <button type="submit" class="btn btn-primary mt-3">Actualizar Factura</button>
      <a href="{{ route('facturas.index') }}" class="btn btn-outline-secondary mt-3">Cancelar</a>
    </form>
  </div>
</div>
@endsection