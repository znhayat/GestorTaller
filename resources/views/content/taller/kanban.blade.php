@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Tablero Kanban</h4>
    <div>
      <a href="{{ route('encargos.index') }}" class="btn btn-outline-secondary me-2">Ver Lista Clásica</a>
      <a href="{{ route('encargos.create') }}" class="btn btn-primary">Nuevo Trabajo</a>
    </div>
  </div>

  <div class="row">
    @foreach(['Pendiente' => 'warning', 'En Proceso' => 'primary', 'Finalizado' => 'success'] as $status => $color)
    <div class="col-md-4">
      <div class="card bg-light shadow-none">
        <div class="card-header bg-{{ $color }} p-2 text-center">
          <h6 class="mb-0 text-white text-uppercase">{{ $status }}</h6>
        </div>
        <div class="card-body p-2" style="min-height: 400px;">
          @foreach($encargos->where('estado', $status) as $e)
          <div class="card mb-2 shadow-sm border-start border-{{ $color }} border-3">
            <div class="card-body p-2">
              <small class="text-muted fw-bold">{{ $e->vehiculo->marca }}</small>
              <div class="small">{{ $e->vehiculo->modelo }}</div>
              <hr class="my-1">
              <div class="small text-dark">{{ $e->vehiculo->cliente->nombre }}</div>

              <div class="mt-2 d-flex justify-content-end">
                <form action="{{ route('encargos.updateStatus', $e->id) }}" method="POST">
                  @csrf
                  @if($e->estado == 'Pendiente')
                  <input type="hidden" name="estado" value="En Proceso">
                  <button type="submit" class="btn btn-xs btn-primary">Empezar</button>
                  @elseif($e->estado == 'En Proceso')
                  <input type="hidden" name="estado" value="Finalizado">
                  <button type="submit" class="btn btn-xs btn-success">Terminar</button>
                  @endif
                </form>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection