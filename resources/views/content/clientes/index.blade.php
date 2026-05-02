@extends('layouts/contentNavbarLayout')

@section('title', 'Lista de Clientes')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Clientes Registrados</h5>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">Añadir Cliente</a>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table" aria-label="Listado de clientes registrados">
      <thead>
        <tr>
          <th># ID</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Teléfono</th>
          <th>Correo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        {{-- Itero sobre la colección de clientes que nos envía el controlador --}}
        @foreach($clientes as $cliente)
        <tr>
          <td><strong class="text-primary">#{{ $cliente->id }}</strong></td>
          <td><strong>{{ $cliente->nombre }}</strong></td>
          <td>{{ $cliente->apellido }}</td>
          <td>{{ $cliente->telefono }}</td>
          <td>{{ $cliente->correo }}</td>
          <td>
            <div class="d-flex align-items-center">
              {{-- Botón nos lleva al formulario con los datos cargados --}}
              <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-primary btn-sm me-2" aria-label="Editar cliente {{ $cliente->nombre }} {{ $cliente->apellido }}">
                <i class="ri-pencil-line me-1" aria-hidden="true"></i> Editar
              </a>

              {{-- Formulario de eliminación enviamos el ID por método DELETE --}}
              <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="btn btn-sm btn-outline-danger d-flex align-items-center"
                  {{-- Doble confirmación para evitar borrar el historial de un cliente por error --}}
                  onclick="return confirm('¿Estás seguro de que deseas eliminar a {{ addslashes($cliente->nombre) }}? Esta acción no se puede deshacer.')"
                  title="Eliminar cliente"
                  aria-label="Eliminar a {{ $cliente->nombre }} {{ $cliente->apellido }}">
                  <i class="ri-delete-bin-line me-1" aria-hidden="true"></i> Eliminar
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