@extends('layouts/contentNavbarLayout')

@section('title', 'Subir Nueva Foto')

@section('content')
<h4 class="fw-bold py-3 mb-4" style="font-family: 'Montserrat', sans-serif;">
  <span class="text-muted fw-light">Fotos /</span> Subir Registro Visual
</h4>

<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0" style="font-family: 'Montserrat', sans-serif;">Detalles de la Imagen</h5>
        <small class="text-muted float-end">Formatos: JPG, PNG</small>
      </div>
      <div class="card-body">
        <form action="{{ route('fotos.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          {{-- Selección de la Orden de Trabajo (OT) --}}
          <div class="mb-3">
            <label class="form-label">Seleccionar Orden de Trabajo (Encargo)</label>
            <select name="encargo_id" class="form-select @error('encargo_id') is-invalid @enderror" required>
              <option value="">Selecciona un encargo...</option>
              @foreach($encargos as $e)
              <option value="{{ $e->id }}">
                OT #{{ $e->id }} - {{ $e->vehiculo->marca }} ({{ $e->vehiculo->cliente->nombre }})
              </option>
              @endforeach
            </select>
            @error('encargo_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Input de archivo con restricción visual (accept="image/*") --}}
          <div class="mb-3">
            <label class="form-label">Archivo de Imagen</label>
            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*" required>
            <div class="form-text">Máximo 2MB. Procure que la imagen esté bien iluminada.</div>
            @error('foto')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción del Trabajo</label>
            <textarea name="descripcion" class="form-control" rows="3" placeholder="Ej: Estado del techo antes de limpiar..."></textarea>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-primary me-2">
              <i class="ri-upload-cloud-2-line me-1"></i> Subir Foto
            </button>
            <a href="{{ route('fotos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection