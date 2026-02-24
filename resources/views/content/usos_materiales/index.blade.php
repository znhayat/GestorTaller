@extends('layouts/contentNavbarLayout')

@section('title', 'Historial de Materiales Usados')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="mb-0">Historial de Consumo de Materiales</h5>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Material</th>
          <th>Cantidad</th>
          <th>Costo</th>
          <th>Orden de Trabajo</th>
        </tr>
      </thead>
      <tbody>
        @foreach($usos as $uso)
        <tr>
          {{-- Fecha del registro para trazabilidad --}}
          <td>{{ $uso->created_at->format('d/m/Y') }}</td>
          <td><strong>{{ $uso->material->nombre }}</strong></td>
          {{-- Concatenamos la cantidad con la unidad (ej: 2.5 Metros) --}}
          <td>{{ $uso->cantidad }} {{ $uso->material->unidad }}</td>
          {{-- Badge verde para el costo total (Precio Unitario x Cantidad) --}}
          <td><span class="badge bg-label-success">{{ number_format($uso->costo_total, 2) }}€</span></td>
          <td>
            {{-- Enlace directo a la orden para ver los detalles del vehículo rápidamente --}}
            <a href="{{ route('encargos.edit', $uso->encargo_id) }}">
              #{{ $uso->encargo_id }} - {{ $uso->encargo->vehiculo->marca }}
            </a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection