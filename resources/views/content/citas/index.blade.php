@extends('layouts/contentNavbarLayout')

@section('title', 'Agenda de Citas')

@section('content')
<div class="card">
  {{-- Cabecera de la tabla con el botón para crear citas nuevas --}}
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Agenda de Citas</h5>
    <a href="{{ route('citas.create') }}" class="btn btn-primary">
      <i class="ri-add-line me-1"></i> Nueva Cita
    </a>
  </div>

  {{-- Contenedor con scroll para que en móviles no se rompa el diseño --}}
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Fecha y Hora</th>
          <th>Vehículo</th>
          <th>Cliente</th>
          <th>Trabajo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($citas as $cita)
        <tr>
          {{-- Pintamos la fecha y la hora con badges de colores para que resalten --}}
          <td>
            <span class="badge bg-label-primary">{{ $cita->fecha }}</span>
            <span class="badge bg-label-secondary">{{ $cita->hora }}</span>
          </td>

          {{-- Sacamos los datos del coche y del cliente navegando por las relaciones del modelo --}}
          <td>{{ $cita->encargo->vehiculo->marca }} {{ $cita->encargo->vehiculo->modelo }}</td>
          <td>{{ $cita->encargo->vehiculo->cliente->nombre }}</td>

          {{-- Acortamos el texto de la avería para que la fila no se haga gigante --}}
          <td><small>{{ Str::limit($cita->encargo->descripcion, 25) }}</small></td>

          <td>
            <div class="d-flex">
              {{-- Botón para ir a la pantalla de editar --}}
              <a href="{{ route('citas.edit', $cita->id) }}" class="btn btn-sm btn-primary me-2">
                <i class="ri-pencil-line me-1"></i> Editar
              </a>

              {{-- Formulario para borrar la cita con aviso de confirmación --}}
              <form action="{{ route('citas.destroy', $cita->id) }}" method="POST" onsubmit="return confirm('¿Cancelar esta cita?')">
                @csrf @method('DELETE')
                <button type="submit"
                  class="btn btn-sm btn-outline-danger d-flex align-items-center"
                  {{-- Doble confirmación por seguridad --}}
                  onclick="return confirm('¿Estás seguro de que deseas eliminar esta cita? Esta acción no se puede deshacer.')"
                  title="Eliminar cita">
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