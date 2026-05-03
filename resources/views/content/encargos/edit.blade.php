@extends('layouts/contentNavbarLayout')

@section('title', 'Editar Encargo')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Encargos /</span> Editar Orden #{{ $encargo->id }}</h4>

{{-- SECCIÓN 1: Datos Generales de la Orden --}}
<div class="card mb-4">
  <div class="card-body">
    <form action="{{ route('encargos.update', $encargo->id) }}" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" name="origin" value="{{ request('origin') }}">
      <div class="row">
        {{-- Selección de vehículo: vincula la orden a un coche y su dueño --}}
        <div class="mb-3 col-md-12">
          <label class="form-label">Vehículo y Dueño</label>
          <select name="vehiculo_id" class="form-select" required>
            @foreach($vehiculos as $v)
            <option value="{{ $v->id }}" {{ $encargo->vehiculo_id == $v->id ? 'selected' : '' }}>
              {{ $v->marca }} {{ $v->modelo }} — ({{ $v->cliente->nombre ?? 'Sin dueño' }})
            </option>
            @endforeach
          </select>
        </div>

        {{-- Área de texto para detallar qué le pasa al coche --}}
        <div class="mb-3 col-md-12">
          <label class="form-label">Descripción del Trabajo</label>
          <textarea name="descripcion" class="form-control" rows="3" required>{{ $encargo->descripcion }}</textarea>
        </div>

        {{-- Control de flujo: Pendiente, En Proceso, etc. --}}
        <div class="mb-3 col-md-4">
          <label class="form-label">Estado Actual</label>
          <select name="estado" class="form-select">
            <option value="Pendiente" {{ $encargo->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="En Proceso" {{ $encargo->estado == 'En Proceso' ? 'selected' : '' }}>En Proceso</option>
            <option value="Esperando Piezas" {{ $encargo->estado == 'Esperando Piezas' ? 'selected' : '' }}>Esperando Piezas</option>
            <option value="Terminado" {{ $encargo->estado == 'Terminado' ? 'selected' : '' }}>Terminado</option>
            <option value="Entregado" {{ $encargo->estado == 'Entregado' ? 'selected' : '' }}>Entregado</option>
          </select>
        </div>

        {{-- Fechas de control para medir tiempos de reparación --}}
        <div class="mb-3 col-md-4">
          <label class="form-label">Fecha de Entrada</label>
          <input type="date" name="fecha_entrada" class="form-control" value="{{ $encargo->fecha_entrada }}">
        </div>
        <div class="mb-3 col-md-4">
          <label class="form-label">Fecha de Salida (Cierre)</label>
          <input type="date" name="fecha_salida" class="form-control" value="{{ $encargo->fecha_salida }}">
        </div>
      </div>
      <div class="mt-4">
        @php
          $origen = request('origin');
          if ($origen == 'recepcion') $urlVolver = route('encargos.recepcion');
          elseif ($origen == 'produccion') $urlVolver = route('encargos.produccion');
          else $urlVolver = route('encargos.index');
        @endphp
        <button type="submit" class="btn btn-primary me-2"><i class="ri-save-line me-1"></i> Guardar Cambios y Volver</button>
        <a href="{{ $urlVolver }}" class="btn btn-outline-secondary">Cancelar y Volver</a>
      </div>
    </form>
  </div>
</div>

@php
  $esDesdeTablero = in_array(request('origin'), ['recepcion', 'produccion']);
@endphp

@if(!$esDesdeTablero)
<div class="row">
  {{-- SECCIÓN 2: Gestión de Materiales (Costos) --}}
  <div class="col-md-7">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Materiales Utilizados</h5>
        {{-- Cálculo automático del total de materiales en tiempo real --}}
        <h5 class="mb-0 text-primary">Total: {{ number_format($encargo->usos_materiales->sum('costo_total'), 2) }}€</h5>
      </div>
      <div class="card-body">
        {{-- Formulario rápido para añadir piezas o lubricantes a la orden --}}
        <form action="{{ route('usos_materiales.store') }}" method="POST" class="row g-3 mb-4">
          @csrf
          <input type="hidden" name="encargo_id" value="{{ $encargo->id }}">
          <div class="col-md-7">
            <select name="material_id" class="form-select" required>
              <option value="">Seleccionar Material...</option>
              @foreach($materiales_lista as $m)
              <option value="{{ $m->id }}">{{ $m->nombre }} ({{ $m->precio_unitario }}€/{{ $m->unidad }})</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <input type="number" step="0.01" name="cantidad" class="form-control" placeholder="Cant." required>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="ri-add-line"></i></button>
          </div>
        </form>

        {{-- Tabla de materiales ya asignados a esta reparación --}}
        <div class="table-responsive">
          <table class="table table-sm table-hover">
            <thead>
              <tr>
                <th>Material</th>
                <th>Cant.</th>
                <th>Costo</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($encargo->usos_materiales as $uso)
              <tr>
                <td>{{ $uso->material->nombre }}</td>
                <td>{{ $uso->cantidad }}</td>
                <td>{{ number_format($uso->costo_total, 2) }}€</td>
                <td class="text-end">
                  <form action="{{ route('usos_materiales.destroy', $uso->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm text-danger border-0"><i class="ri-delete-bin-line me-1"></i> Eliminar</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center">No hay materiales cargados.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- SECCIÓN 3: Galería Fotográfica (Evidencias) --}}
  <div class="col-md-5">
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="mb-0">Galería de Fotos</h5>
      </div>
      <div class="card-body">
        {{-- Subida de archivos con soporte para imágenes --}}
        <form action="{{ route('fotos.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
          @csrf
          <input type="hidden" name="encargo_id" value="{{ $encargo->id }}">
          <div class="mb-3">
            <input type="file" name="foto" class="form-control" accept="image/*" required>
          </div>
          <div class="input-group">
            <input type="text" name="descripcion" class="form-control" placeholder="Descripción de la foto...">
            <button type="submit" class="btn btn-dark"><i class="ri-upload-2-line"></i></button>
          </div>
        </form>

        {{-- Visualización de miniaturas de la reparación --}}
        <div class="row g-2">
          @forelse($encargo->fotos as $f)
          <div class="col-6">
            <div class="position-relative border rounded">
              <img src="{{ asset('storage/' . $f->ruta) }}" class="img-fluid rounded" style="height: 100px; width: 100%; object-fit: cover;">
              {{-- Botón rápido para eliminar fotos incorrectas --}}
              <form action="{{ route('fotos.destroy', $f->id) }}" method="POST" class="position-absolute top-0 end-0 p-1">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger p-0 px-1"><i class="ri-close-line"></i></button>
              </form>
            </div>
          </div>
          @empty
          <p class="text-center text-muted">Aún no hay fotos del trabajo.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
@endif
@endsection