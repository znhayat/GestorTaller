@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Vehículos en Taller</h5>
    <a href="{{ route('vehiculos.create') }}" class="btn btn-primary">Registrar Vehículo</a>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Dueño</th>
          <th>Marca</th>
          <th>Modelo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($vehiculos as $v)
        <tr>
          {{-- Relación Eloquent: Accedemos al nombre del cliente a través del objeto vehículo --}}
          <td><strong>{{ $v->cliente->nombre }}</strong></td>
          <td>{{ $v->marca }}</td>
          <td>{{ $v->modelo }}</td>
          <td>
            <div class="d-flex">
              {{-- Botón de edición --}}
              <a href="{{ route('vehiculos.edit', $v->id) }}" class="btn btn-primary btn-sm me-2">
                <i class="ri-pencil-line me-1"></i> Editar
              </a>

              {{-- Eliminación con advertencia para evitar borrar coches con órdenes activas --}}
              <form action="{{ route('vehiculos.destroy', $v->id) }}" method="POST" onsubmit="return confirm('¿Borrar vehículo?')">
                @csrf @method('DELETE')
                <button type="submit"
                  class="btn btn-sm btn-outline-danger d-flex align-items-center"
                  onclick="return confirm('¿Estás seguro? Esta acción no se puede deshacer.')"
                  title="Eliminar vehículo">
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