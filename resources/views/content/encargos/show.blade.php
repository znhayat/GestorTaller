@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container-xxl">
  <h4 class="fw-bold py-3">Detalle del Encargo #{{ $encargo->id }}</h4>

  <div class="row">
    <div class="col-md-8">
      <div class="card mb-4">
        <div class="card-header">
          <h5>Materiales Asociados</h5>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Material</th>
                <th>Cantidad</th>
                <th>Precio</th>
              </tr>
            </thead>
            <tbody>
              @foreach($encargo->materiales as $m)
              <tr>
                <td>{{ $m->nombre }}</td>
                <td>{{ $m->pivot->cantidad }}</td>
                <td>{{ number_format($m->pivot->precio, 2) }}€</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5>Estado: {{ $encargo->estado }}</h5>
          <hr>
          <a href="#" class="btn btn-primary w-100 mb-2">Añadir Material</a>
          <a href="#" class="btn btn-success w-100">Generar Presupuesto</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection