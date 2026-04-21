@extends('layouts/blankLayout')

@section('title', 'Zana Tapicería - Portada')

@section('content')
<!-- Barra de Navegación Simple para Login -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm mb-0">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="#"></a>
    <div class="d-flex ms-auto">
      <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill">
        <i class="ri-user-settings-line me-2"></i> Acceso Equipo
      </a>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<div class="bg-primary text-white py-5 mb-5 text-center position-relative">
  <div class="container py-sm-5">
    <h1 class="display-3 fw-bold text-white mb-3">Dale una nueva vida a tu vehículo</h1>
    <p class="lead mb-4">Especialistas en tapizado automotriz. Recuperamos el interior de tu coche como el primer día.</p>
    <a href="#galeria" class="btn btn-light btn-lg rounded-pill">Ver Nuestros Trabajos</a>
  </div>
</div>

<div class="container">
  
  <!-- Galería de Trabajos (Cards) -->
  <div id="galeria" class="mb-5 pt-3">
    <div class="text-center mb-4">
      <h2 class="fw-bold mb-1">Nuestros Trabajos</h2>
      <p class="text-muted">Galería del antes y después</p>
    </div>
    
    <div class="row">
      @if($fotos->count() > 0)
        @foreach($fotos as $foto)
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-none border">
            <img class="card-img-top" src="{{ asset($foto->ruta) }}" alt="{{ $foto->titulo_galeria }}" style="height: 200px; object-fit: cover;">
            <div class="card-body">
              <h5 class="card-title">{{ $foto->titulo_galeria }}</h5>
              <p class="card-text">{{ $foto->descripcion }}</p>
              <span class="badge bg-label-{{ $foto->categoria_badge }}">{{ $foto->categoria_texto }}</span>
            </div>
          </div>
        </div>
        @endforeach
      @else
        <div class="col-12 text-center py-5">
            <h4 class="text-muted"></h4>
            <p>Estamos construyendo nuestro portfolio digital.</p>
        </div>
      @endif
    </div>
  </div>

  <hr class="mb-5">

  <!-- Contacto y Ubicación -->
  <div class="row justify-content-center mb-5 pb-4">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header border-bottom">
          <h4 class="card-title m-0 text-center"><i class="ri-contacts-book-2-line text-primary me-2"></i> Información de Contacto</h4>
        </div>
        <div class="card-body p-4 pt-5">
          <div class="row">
            <div class="col-sm-6 mb-4 mb-sm-0">
              <div class="d-flex flex-column align-items-center text-center">
                <div class="avatar avatar-md mb-3">
                  <span class="avatar-initial rounded bg-label-primary"><i class="ri-map-pin-line fs-3"></i></span>
                </div>
                <h6 class="mb-1">Ubicación del Taller</h6>
                <p class="mb-0 text-muted"><br></p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="d-flex flex-column align-items-center text-center">
                <div class="avatar avatar-md mb-3">
                  <span class="avatar-initial rounded bg-label-success"><i class="ri-phone-line fs-3"></i></span>
                </div>
                <h6 class="mb-1">Llámanos</h6>
                <p class="mb-0 text-muted"><br></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</div>

<!-- Footer Simple -->
<footer class="bg-light py-4 text-center mt-auto border-top">
  <div class="container">
    <p class="text-muted mb-0">© {{ date('Y') }} Zana Tapicería del Automóvil. Todos los derechos reservados.</p>
  </div>
</footer>
@endsection
