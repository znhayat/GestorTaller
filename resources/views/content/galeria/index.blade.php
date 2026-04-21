@extends('layouts/contentNavbarLayout')

@section('title', 'Gestión de Galería Web')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Marketing /</span> Galería Pública</h4>

<div class="row">
    <!-- Formulario para subir nueva foto -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <h5 class="card-header pb-1">Añadir a la Galería</h5>
            <div class="card-body">
                <form action="{{ route('galeria.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="foto" class="form-label">Imagen (Max 10MB)</label>
                        <input class="form-control" type="file" id="foto" name="foto" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label for="titulo_galeria" class="form-label">Título</label>
                        <input class="form-control" type="text" id="titulo_galeria" name="titulo_galeria" placeholder="Ej: Restauración de Volante" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="2" placeholder="Breve explicación del trabajo realizado..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="categoria_texto" class="form-label">Nombre Categoría</label>
                        <input class="form-control" type="text" id="categoria_texto" name="categoria_texto" placeholder="Ej: Volantes, Asientos..." required>
                    </div>
                    <div class="mb-4">
                        <label for="categoria_badge" class="form-label">Color de Etiqueta</label>
                        <select class="form-select" id="categoria_badge" name="categoria_badge" required>
                            <option value="primary">Azul (Primary)</option>
                            <option value="info">Celeste (Info)</option>
                            <option value="success">Verde (Success)</option>
                            <option value="warning">Amarillo (Warning)</option>
                            <option value="danger">Rojo (Danger)</option>
                            <option value="dark">Negro (Dark)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Subir y Publicar <i class="ri-upload-cloud-2-line ms-2"></i></button>
                </form>
            </div>
        </div>
    </div>

    <!-- Pestaña de fotos actuales -->
    <div class="col-md-8">
        <div class="card">
            <h5 class="card-header">Fotos Publicadas en la Landing Page</h5>
            <div class="card-body">
                @if($fotos->count() > 0)
                <div class="row">
                    @foreach($fotos as $foto)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-none border">
                            <img class="card-img-top" src="{{ asset($foto->ruta) }}" alt="{{ $foto->titulo_galeria }}" style="height: 150px; object-fit: cover;">
                            <div class="card-body p-3">
                                <span class="badge bg-label-{{ $foto->categoria_badge }} mb-2">{{ $foto->categoria_texto }}</span>
                                <h6 class="card-title mb-1">{{ $foto->titulo_galeria }}</h6>
                                <p class="card-text small text-truncate" title="{{ $foto->descripcion }}">{{ $foto->descripcion }}</p>
                                
                                <form action="{{ route('galeria.destroy', $foto->id) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres quitar esta foto de la web pública?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100"><i class="ri-delete-bin-line me-1"></i> Retirar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center p-5">
                    <i class="ri-image-add-line text-muted mb-3" style="font-size: 3rem;"></i>
                    <p class="mb-0 text-muted">Aún no has subido ninguna foto para la portada.<br>Usa el formulario de la izquierda.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
