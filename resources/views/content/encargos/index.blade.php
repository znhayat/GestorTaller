@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Órdenes de Trabajo (Encargos)</h5>
    <a href="{{ route('encargos.create') }}" class="btn btn-primary">Nuevo Encargo</a>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Vehículo (Dueño)</th>
          <th>Descripción</th>
          <th>Estado</th>
          <th>Entrada</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($encargos as $e)
        <tr>
          <td>
            {{-- Mostramos marca y modelo resaltados, y justo debajo el nombre del dueño en pequeño --}}
            <strong>{{ $e->vehiculo->marca }} {{ $e->vehiculo->modelo }}</strong><br>
            <small class="text-muted">{{ $e->vehiculo->cliente->nombre }}</small>
          </td>

          {{-- Limitamos la descripción --}}
          <td>{{ Str::limit($e->descripcion, 30) }}</td>

          {{-- El estado del coche (pendiente, reparando, terminado) con un badge informativo --}}
          <td><span class="badge bg-label-info">{{ $e->estado }}</span></td>

          <td>{{ $e->fecha_entrada }}</td>

          <td>
            <div class="d-flex">
              {{-- Botón para editar los detalles del encargo --}}
              <a href="{{ route('encargos.edit', $e->id) }}" class="btn btn-primary btn-sm me-2">
                <i class="ri-pencil-line me-1"></i> Editar
              </a>

              {{-- Formulario para eliminar el encargo con doble confirmación de seguridad --}}
              <form action="{{ route('encargos.destroy', $e->id) }}" method="POST" onsubmit="return confirm('¿Borrar encargo?')">
                @csrf @method('DELETE')
                <button type="submit"
                  class="btn btn-sm btn-outline-danger d-flex align-items-center"
                  {{-- Mensaje de advertencia para evitar accidentes --}}
                  onclick="return confirm('¿Estás seguro de que deseas eliminar este encargo? Esta acción no se puede deshacer.')"
                  title="Eliminar encargo">
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