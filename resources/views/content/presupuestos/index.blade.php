@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Presupuestos Enviados</h5>
    <a href="{{ route('presupuestos.create') }}" class="btn btn-primary">Nuevo Presupuesto</a>
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
</div>
@endsection