@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container-xxl">
  <div class="d-flex justify-content-between align-items-center py-3 flex-wrap gap-3">
    <h4 class="fw-bold">Inventario de Materiales</h4>
    <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
      <!-- Buscador -->
      <form method="GET" action="{{ route('materiales.index') }}" class="d-flex position-relative me-2" style="min-width: 250px;">
        <input type="text" name="search" class="form-control ps-5 pe-4 w-100" placeholder="Buscar material..." value="{{ request('search') }}">
        <i class="ri-search-line position-absolute" style="top: 50%; transform: translateY(-50%); left: 15px; color: #a1acb8;"></i>
        @if(request('search'))
        <a href="{{ route('materiales.index') }}" class="position-absolute" style="top: 50%; transform: translateY(-50%); right: 10px; color: #a1acb8; cursor: pointer;" title="Limpiar búsqueda">
          <i class="ri-close-circle-line"></i>
        </a>
        @endif
      </form>
      <a href="{{ route('materiales.create') }}" class="btn btn-primary"><i class="ri-add-line me-1"></i> Añadir Material</a>
    </div>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Unidad</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($materiales as $m)
          <tr>
            <td><strong>{{ $m->nombre }}</strong></td>
            <td><span class="badge bg-info">{{ $m->categoria ?? 'General' }}</span></td>
            <td>{{ $m->unidad }}</td>
            <td>{{ number_format($m->precio_unitario, 2) }}€</td>
            <td>{{ $m->stock ?? 'N/A' }}</td>
            <td>
              <div class="d-flex gap-2">
                <a href="{{ route('materiales.edit', $m->id) }}" class="btn btn-sm btn-primary">Editar</a>
                <form action="{{ route('materiales.destroy', $m->id) }}" method="POST" onsubmit="return confirm('¿Seguro?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-4">
              <i class="ri-inbox-line fs-1 text-muted"></i>
              <p class="mt-2">No se encontraron materiales</p>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($materiales->hasPages())
  <div class="mt-3">
    {{ $materiales->appends(['search' => request('search')])->links() }}
  </div>
  @endif
</div>
@endsection