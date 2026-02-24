@extends('layouts/contentNavbarLayout')

@section('title', 'Lista de Clientes')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Clientes Registrados</h5>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">Añadir Cliente</a>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Teléfono</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($clientes as $cliente)
        <tr>
          <td><strong>{{ $cliente->nombre }}</strong></td>
          <td>{{ $cliente->telefono }}</td>
          <td>
            <div class="d-flex align-items-center">
              <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-primary btn-sm me-2">
                <i class="ri-pencil-line me-1"></i> Editar
              </a>

              <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este cliente?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="btn btn-sm btn-outline-danger d-flex align-items-center"
                  onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente? Esta acción no se puede deshacer.')"
                  title="Eliminar cliente">
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