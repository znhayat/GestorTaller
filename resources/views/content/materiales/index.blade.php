@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container-xxl">

  {{-- BLOQUES DE CATEGORÍAS --}}
  @if(!isset($items))
  <div class="d-flex justify-content-between align-items-center py-3">
    <h4 class="fw-bold">Inventario</h4>
    {{-- Botón para añadir nuevo material (y crear nueva categoría si es necesario) --}}
    <a href="{{ route('materiales.create') }}" class="btn btn-primary">
      <i class="ri-add-line me-1"></i> Añadir Elemento/Categoria
    </a>
  </div>

  <div class="row">
    @foreach($categorias as $tipo)
    <div class="col-md-3">
      <a href="{{ route('materiales.categoria', $tipo) }}" class="card text-center mb-4 text-decoration-none shadow-sm h-100">

        <div class="card-body">
          <h5 class="card-title">{{ $tipo }}</h5>
        </div>
      </a>
    </div>
    @endforeach
  </div>
  @endif

  {{-- TABLA DE MATERIALES (Solo visible al entrar en una categoría) --}}
  @if(isset($items))
  <div class="d-flex justify-content-between align-items-center py-3">
    <h4 class="fw-bold">{{ $tipo }}</h4>
    <div>
      <a href="{{ route('materiales.index') }}" class="btn btn-outline-secondary">Volver</a>
      {{-- Si quieres que el botón de añadir ya venga con la categoría preseleccionada, 
           puedes pasarle el tipo como parámetro en la URL --}}
      <a href="{{ route('materiales.create', ['tipo' => $tipo]) }}" class="btn btn-primary">Añadir Material</a>
    </div>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Unidad</th>
            <th>Precio</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($items as $m)
          <tr>
            <td><strong>{{ $m->nombre }}</strong></td>
            <td>{{ $m->unidad }}</td>
            <td>{{ number_format($m->precio_unitario, 2) }}€</td>
            <td>
              <div class="d-flex">
                <a href="{{ route('materiales.edit', $m->id) }}" class="btn btn-primary btn-sm me-2">Editar</a>
                <form action="{{ route('materiales.destroy', $m->id) }}" method="POST" onsubmit="return confirm('¿Seguro?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif
</div>
@endsection