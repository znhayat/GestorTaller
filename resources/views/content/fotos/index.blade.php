@extends('layouts/contentNavbarLayout')

@section('title', 'Galería de Trabajos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="fw-bold" style="font-family: 'Montserrat', sans-serif;">Historial Visual de Trabajos</h4>
  <a href="{{ route('fotos.create') }}" class="btn btn-primary">Subir Nueva Foto</a>
</div>

<div class="row">
  @forelse($fotos as $foto)
  <div class="col-md-4 mb-4">
    <div class="card h-100 shadow-sm">
      {{-- La imagen del trabajo: object-fit cover asegura que no se deforme --}}
      <img class="card-img-top" src="{{ asset('storage/' . $foto->ruta) }}" style="height: 200px; object-fit: cover;">

      <div class="card-body">
        <h6 class="fw-bold" style="font-family: 'Montserrat', sans-serif;">
          {{-- Referencia rápida a la Orden de Trabajo y marca del coche --}}
          Encargo #{{ $foto->encargo_id }} - {{ $foto->encargo->vehiculo->marca }}
        </h6>
        <p class="text-muted small">{{ $foto->descripcion ?? 'Sin descripción' }}</p>

        @if(Auth::user()->role === 'admin')
        <div class="d-flex justify-content-between mt-3">
          {{-- Botón de edición --}}
          <a href="{{ route('fotos.edit', $foto->id) }}" class="btn btn-primary btn-sm me-2">
            <i class="ri-pencil-line me-1"></i> Editar
          </a>
          {{-- Eliminación de la imagen del servidor --}}
          <form action="{{ route('fotos.destroy', $foto->id) }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Borrar foto?')">Eliminar</button>
          </form>
        </div>
        @endif
      </div>
    </div>
  </div>
  @empty
  {{-- Estado vacío por si no hay fotos todavía --}}
  <div class="col-12 text-center">
    <h5>Aún no hay fotos cargadas.</h5>
  </div>
  @endforelse
</div>
@endsection