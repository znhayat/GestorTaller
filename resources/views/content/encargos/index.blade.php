@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <h5 class="mb-0">Órdenes de Trabajo (Encargos)</h5>
    <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
      <!-- Buscador -->
      <form method="GET" action="{{ route('encargos.index') }}" class="d-flex position-relative me-2" style="min-width: 250px;">
        <input type="text" name="search" class="form-control ps-5 pe-4 w-100" placeholder="Buscar por cliente, vehículo..." value="{{ request('search') }}">
        <i class="ri-search-line position-absolute" style="top: 50%; transform: translateY(-50%); left: 15px; color: #a1acb8;"></i>
        @if(request('search'))
        <a href="{{ route('encargos.index') }}" class="position-absolute" style="top: 50%; transform: translateY(-50%); right: 10px; color: #a1acb8; cursor: pointer;" title="Limpiar búsqueda">
          <i class="ri-close-circle-line"></i>
        </a>
        @endif
      </form>

      <a href="{{ route('encargos.recepcion') }}" class="btn btn-outline-primary"><i class="ri-phone-line me-1"></i> Recepción</a>
      <a href="{{ route('encargos.produccion') }}" class="btn btn-outline-primary"><i class="ri-tools-line me-1"></i> Producción</a>
      <a href="{{ route('encargos.create') }}" class="btn btn-primary"><i class="ri-add-line me-1"></i> Nuevo</a>
    </div>
  </div>

  @if(session('success'))
  <div class="alert alert-success m-3">
    {{ session('success') }}
  </div>
  @endif
  @if(session('error'))
  <div class="alert alert-danger m-3">
    {{ session('error') }}
  </div>
  @endif

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
  <div class="card-footer d-flex justify-content-center">
    {{ $encargos->appends(['search' => request('search')])->links() }}
  </div>
</div>
@endsection