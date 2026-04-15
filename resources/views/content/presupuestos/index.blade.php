@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <h5 class="mb-0">Presupuestos Enviados</h5>
    <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
      <!-- Buscador -->
      <form method="GET" action="{{ route('presupuestos.index') }}" class="d-flex position-relative me-2" style="min-width: 250px;">
        <input type="text" name="search" class="form-control ps-5 pe-4 w-100" placeholder="Buscar por cliente..." value="{{ request('search') }}">
        <i class="ri-search-line position-absolute" style="top: 50%; transform: translateY(-50%); left: 15px; color: #a1acb8;"></i>
        @if(request('search'))
        <a href="{{ route('presupuestos.index') }}" class="position-absolute" style="top: 50%; transform: translateY(-50%); right: 10px; color: #a1acb8; cursor: pointer;" title="Limpiar búsqueda">
          <i class="ri-close-circle-line"></i>
        </a>
        @endif
      </form>

      <a href="{{ route('presupuestos.create') }}" class="btn btn-primary"><i class="ri-add-line me-1"></i> Nuevo Presupuesto</a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Encargo</th>
          <th>Materiales</th>
          <th>Mano de Obra</th>
          <th>Total</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($presupuestos as $p)
        <tr>
          <td>
            {{-- Vinculamos visualmente el presupuesto con la Orden de Trabajo y el Cliente --}}
            <strong>#{{ $p->encargo_id }}</strong> - {{ $p->encargo->vehiculo->cliente->nombre }}
          </td>
          {{-- Desglose de costes --}}
          <td>{{ number_format($p->precio_materiales, 2) }}€</td>
          <td>{{ number_format($p->precio_horas, 2) }}€</td>
          {{-- El total suele ser un campo calculado en el modelo o controlador --}}
          <td><strong>{{ number_format($p->total, 2) }}€</strong></td>

          <td>
            {{-- Badge semántico: Amarillo para espera, Verde para aprobado --}}
            @if($p->aceptado)
            <span class="badge bg-label-success">Aceptado</span>
            @else
            <span class="badge bg-label-warning">Pendiente</span>
            @endif
          </td>

          <td>
            <div class="d-flex gap-2">
              <a href="{{ route('presupuestos.edit', $p->id) }}" class="btn btn-sm btn-primary">Editar</a>
              <form action="{{ route('presupuestos.destroy', $p->id) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar?')">Borrar</button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="card-footer d-flex justify-content-center">
    {{ $presupuestos->appends(['search' => request('search')])->links() }}
  </div>
</div>
@endsection