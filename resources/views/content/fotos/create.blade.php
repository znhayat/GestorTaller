@extends('layouts/contentNavbarLayout')

@section('title', 'Subir Nueva Foto')

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Fotos /</span> Subir Registro Visual
</h4>

<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Configuración de la Imagen</h5>
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
          </div>

          <div class="row">
            {{-- Nombre de Categoría (Igual que en el Step del Wizard) --}}
            <div class="mb-3 col-md-6">
                <label class="form-label">Categoría (Ej: Volantes, Asientos...)</label>
                <select name="categoria_texto" class="form-select" id="categoriaSelect" onchange="toggleNuevaCategoria(this)" required>
                    <option value="" disabled selected>Selecciona una categoría...</option>
                    <option value="Asientos">Asientos</option>
                    <option value="Techo / Cielo">Techo / Cielo</option>
                    <option value="Puertas">Puertas</option>
                    <option value="Volante / Cambio">Volante / Cambio</option>
                    <option value="Suelo / Alfombras">Suelo / Alfombras</option>
                    <option value="Capotas (Cabrio)">Capotas (Cabrio)</option>
                    <option value="Náutica">Náutica</option>
                    <option value="Motos">Motos</option>
                    <option value="OTRO" class="text-primary fw-bold">+ Añadir categoría personalizada...</option>
                </select>
                <input type="text" name="nueva_categoria" id="nuevaCategoriaInput" class="form-control mt-2" 
                       placeholder="Escribe el nombre de la nueva categoría" style="display:none;">
            </div>

            {{-- Color de Etiqueta (Badge) --}}
            <div class="mb-3 col-md-6">
                <label class="form-label">Color de Etiqueta (En la web)</label>
                <select name="categoria_badge" class="form-select" required>
                    <option value="primary">Azul (Principal)</option>
                    <option value="success">Verde (Terminado)</option>
                    <option value="info">Celeste (Información)</option>
                    <option value="warning">Amarillo (Aviso)</option>
                    <option value="danger">Rojo (Urgente)</option>
                    <option value="secondary">Gris (Neutral)</option>
                    <option value="dark">Negro (Elegante)</option>
                </select>
            </div>
          </div>

          {{-- Input de archivo con Previsualización --}}
          <div class="mb-3 text-center d-none" id="previewContainer">
            <label class="form-label d-block text-start">Vista Previa</label>
            <img id="imagePreview" src="#" alt="Vista previa" class="img-fluid rounded shadow-sm mb-3" style="max-height: 250px;">
          </div>

          <div class="mb-3">
            <label class="form-label">Archivo de Imagen</label>
            <input type="file" id="fotoInput" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*" required onchange="previewImage(this)">
            <div class="form-text">Máximo 2MB. Procure que la imagen esté bien iluminada.</div>
          </div>

          <div class="mb-3">
            <label class="form-label">Título del Trabajo (Opcional)</label>
            <input type="text" name="titulo_galeria" class="form-control" placeholder="Ej: Tapizado en cuero Napa Negro">
          </div>

          <div class="mt-4 d-grid d-sm-block">
            <button type="submit" class="btn btn-primary btn-lg me-sm-2 mb-2 mb-sm-0">
              <i class="ri-upload-cloud-2-line me-1"></i> Subir Foto al Portfolio
            </button>
            <a href="{{ route('fotos.index') }}" class="btn btn-outline-secondary btn-lg">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const container = document.getElementById('previewContainer');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.classList.remove('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleNuevaCategoria(select) {
    const input = document.getElementById('nuevaCategoriaInput');
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
@endsection