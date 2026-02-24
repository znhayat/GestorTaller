@extends('layouts/contentNavbarLayout')

@section('title', 'Editar Foto')

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 style="font-family: 'Montserrat', sans-serif;">Editar Información</h5>
      </div>
      <div class="card-body">
        {{-- ¡IMPORTANTE! enctype="multipart/form-data" es obligatorio para subir archivos --}}
        <form action="{{ route('fotos.update', $foto->id) }}" method="POST" enctype="multipart/form-data">
          @csrf @method('PUT')

          {{-- Permite reasignar la foto a otra orden si hubo un error --}}
          <div class="mb-3">
            <label class="form-label">Orden de Trabajo</label>
            <select name="encargo_id" class="form-select">
              @foreach($encargos as $e)
              <option value="{{ $e->id }}" {{ $foto->encargo_id == $e->id ? 'selected' : '' }}>
                #{{ $e->id }} - {{ $e->vehiculo->marca }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Cambiar Foto (Opcional)</label>
            <input type="file" name="foto" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control">{{ $foto->descripcion }}</textarea>
          </div>

          <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
      </div>
    </div>
  </div>

  {{-- Vista previa lateral de la foto que ya existe en el servidor --}}
  <div class="col-md-4 text-center">
    <label class="d-block mb-2">Imagen Actual</label>
    <img src="{{ asset('storage/' . $foto->ruta) }}" class="img-fluid rounded border shadow-sm">
  </div>
</div>
@endsection