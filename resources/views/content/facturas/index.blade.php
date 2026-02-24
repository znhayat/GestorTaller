@extends('layouts/contentNavbarLayout')

@section('title', 'Facturación')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0" style="font-family: 'Montserrat', sans-serif;">Historial de Facturas</h5>
    <a href="{{ route('facturas.create') }}" class="btn btn-primary">Nueva Factura</a>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th># Factura</th>
          <th>Cliente / Vehículo</th>
          <th>Importe Total</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($facturas as $f)
        <tr>
          {{-- ID de la factura resaltado --}}
          <td><strong>#{{ $f->id }}</strong></td>

          <td>
            {{-- Mostramos el cliente y, justo debajo, los datos del coche asociado al encargo --}}
            <span class="fw-medium">{{ $f->encargo->vehiculo->cliente->nombre }}</span><br>
            <small class="text-muted">{{ $f->encargo->vehiculo->marca }} ({{ $f->encargo->vehiculo->matricula }})</small>
          </td>

          {{-- Formateo de moneda para asegurar los dos decimales del importe --}}
          <td>{{ number_format($f->importe_total, 2) }}€</td>

          <td>
            {{-- Badge condicional: verde si está pagada, rojo si está pendiente --}}
            @if($f->pagado)
            <span class="badge bg-label-success">Pagado</span>
            @else
            <span class="badge bg-label-danger">Pendiente</span>
            @endif
          </td>

          <td>
            <div class="d-flex align-items-center gap-2">
              {{-- Acceso rápido para modificar datos de la factura o el estado del pago --}}
              <a href="{{ route('facturas.edit', $f->id) }}"
                class="btn btn-sm btn-primary d-flex align-items-center"
                title="Editar factura">
                <i class="ri-edit-line me-1"></i> Editar
              </a>

              {{-- Borrado con advertencia de seguridad para evitar descuadres contables por error --}}
              <form action="{{ route('facturas.destroy', $f->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="btn btn-sm btn-outline-danger d-flex align-items-center"
                  onclick="return confirm('¿Estás seguro de que deseas eliminar esta factura? Esta acción no se puede deshacer.')"
                  title="Eliminar factura">
                  <i class="ri-delete-bin-line me-1"></i> Eliminar
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection