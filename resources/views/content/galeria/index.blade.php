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
                    <!-- Switch Antes y Después -->
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="es_antes_despues" name="es_antes_despues" onchange="toggleAntesDespues(this)">
                        <label class="form-check-label fw-bold" for="es_antes_despues">Modo Antes y Después</label>
                    </div>

                    <div id="single-upload">
                        <div class="mb-3">
                            <label for="foto" class="form-label">Imagen Única (Max 10MB)</label>
                            <input class="form-control" type="file" id="foto" name="foto" accept="image/*">
                        </div>
                    </div>

                    <div id="dual-upload" style="display: none;">
                        <div class="mb-3">
                            <label for="foto_antes" class="form-label text-warning">Foto ANTES</label>
                            <input class="form-control border-warning" type="file" id="foto_antes" name="foto_antes" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="foto_despues" class="form-label text-success">Foto DESPUÉS</label>
                            <input class="form-control border-success" type="file" id="foto_despues" name="foto_despues" accept="image/*">
                        </div>
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
                        <select class="form-select" id="categoria_texto" name="categoria_texto" onchange="toggleNuevaCategoriaMarketing(this)" required>
                            <option value="" disabled selected>Selecciona una categoría...</option>
                            <option value="Asientos">Asientos</option>
                            <option value="Techo / Cielo">Techo / Cielo</option>
                            <option value="Puertas">Puertas</option>
                            <option value="Volante / Cambio">Volante / Cambio</option>
                            <option value="Suelo / Alfombras">Suelo / Alfombras</option>
                            <option value="Capotas (Cabrio)">Capotas (Cabrio)</option>
                            <option value="OTRO" class="text-primary fw-bold">+ Añadir nueva categoría...</option>
                        </select>
                        <input type="text" name="nueva_categoria" id="nuevaCategoriaMarketing" class="form-control mt-2" 
                               placeholder="Escribe el nombre de la nueva categoría" style="display:none;">
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

                <script>
                function toggleAntesDespues(checkbox) {
                    const single = document.getElementById('single-upload');
                    const dual = document.getElementById('dual-upload');
                    const inputFoto = document.getElementById('foto');
                    const inputAntes = document.getElementById('foto_antes');
                    const inputDespues = document.getElementById('foto_despues');

                    if (checkbox.checked) {
                        single.style.display = 'none';
                        dual.style.display = 'block';
                        inputFoto.required = false;
                        inputAntes.required = true;
                        inputDespues.required = true;
                    } else {
                        single.style.display = 'block';
                        dual.style.display = 'none';
                        inputFoto.required = true;
                        inputAntes.required = false;
                        inputDespues.required = false;
                    }
                }

                function toggleNuevaCategoriaMarketing(select) {
                    const input = document.getElementById('nuevaCategoriaMarketing');
                    if (select.value === 'OTRO') {
                        input.style.display = 'block';
                        input.required = true;
                        select.name = ""; 
                        input.name = "categoria_texto";
                    } else {
                        input.style.display = 'none';
                        input.required = false;
                        select.name = "categoria_texto"; 
                        input.name = "nueva_categoria";
                    }
                }
                </script>
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
                            <div class="position-relative">
                                <img class="card-img-top" src="{{ asset('storage/' . $foto->ruta) }}" alt="{{ $foto->titulo_galeria }}" style="height: 150px; object-fit: cover;">
                                @if($foto->tipo === 'antes')
                                    <span class="badge bg-warning position-absolute top-0 end-0 m-2">ANTES</span>
                                @elseif($foto->tipo === 'despues')
                                    <span class="badge bg-success position-absolute top-0 end-0 m-2">DESPUÉS</span>
                                @endif
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-label-{{ $foto->categoria_badge }}">{{ $foto->categoria_texto }}</span>
                                    @if($foto->tipo !== 'normal')
                                        <small class="text-muted"><i class="ri-links-line"></i> Vinculada</small>
                                    @endif
                                </div>
                                <h6 class="card-title mb-1">{{ $foto->titulo_galeria }}</h6>
                                <p class="card-text small text-truncate" title="{{ $foto->descripcion }}">{{ $foto->descripcion }}</p>
                                
                                <form action="{{ route('galeria.destroy', $foto->id) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres quitar esta foto de la web pública?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100"><i class="ri-delete-bin-line me-1"></i> Eliminar</button>
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
