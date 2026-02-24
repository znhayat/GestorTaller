@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Inventario de Materiales</h5>
    <a href="{{ route('materiales.create') }}" class="btn btn-primary">Nuevo Material</a>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Tipo</th>
          <th>Unidad</th>
          <th>Precio Unit.</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($materiales as $m)
        <tr>
          {{-- Nombre del material resaltado --}}
          <td><strong>{{ $m->nombre }}</strong></td>
          {{-- Badge para identificar visualmente la categoría (Tela, Herramienta, etc.) --}}
          <td><span class="badge bg-label-primary">{{ $m->tipo }}</span></td>
          <td>{{ $m->unidad }}</td>
          {{-- Formateo de precio con dos decimales y el símbolo de Euro --}}
          <td>{{ number_format($m->precio_unitario, 2) }}€</td>
          <td>
            <div class="d-flex">
              {{-- Acceso a la edición del material --}}
              <a href="{{ route('materiales.edit', $m->id) }}" class="btn btn-primary btn-sm me-2">
                <i class="ri-pencil-line me-1"></i> Editar
              </a>
              {{-- Borrado con doble confirmación de seguridad --}}
              <form action="{{ route('materiales.destroy', $m->id) }}" method="POST" onsubmit="return confirm('¿Borrar material?')">
                @csrf @method('DELETE')
                <button type="submit"
                  class="btn btn-sm btn-outline-danger d-flex align-items-center"
                  onclick="return confirm('¿Estás seguro de que deseas eliminar este material?')"
                  title="Eliminar material">
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