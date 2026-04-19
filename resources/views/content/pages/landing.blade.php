@extends('layouts/blankLayout')

@section('title', 'Zana Tapicería - Portada')

@section('content')
<!-- Barra de Navegación Simple para Login -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm mb-0">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="#">ZANA TAPICERÍA</a>
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
    <p class="lead mb-4">Especialistas en tapizado automotriz premium. Recuperamos el interior de tu coche como el primer día.</p>
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
      <!-- Card Trabajo 1 -->
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-none border">
          <img class="card-img-top" src="{{ asset('assets/img/elements/1.jpg') }}" alt="Trabajo volante" style="height: 200px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title">Restauración de Volante</h5>
            <p class="card-text">Retapizado completo en cuero de primera calidad con costuras deportivas rojas. Desgaste 100% eliminado.</p>
            <span class="badge bg-label-primary">Volantes</span>
          </div>
        </div>
      </div>
      
      <!-- Card Trabajo 2 -->
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-none border">
          <img class="card-img-top" src="{{ asset('assets/img/elements/2.jpg') }}" alt="Trabajo asientos" style="height: 200px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title">Juego de Asientos</h5>
            <p class="card-text">Cambio de tapicería de tela a cuero sintético premium reforzado. Adaptación a medida para máximo confort.</p>
            <span class="badge bg-label-info">Asientos</span>
          </div>
        </div>
      </div>
      
      <!-- Card Trabajo 3 -->
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-none border">
          <img class="card-img-top" src="{{ asset('assets/img/elements/3.jpg') }}" alt="Trabajo techos" style="height: 200px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title">Tapizado de Techo Caído</h5>
            <p class="card-text">Solución completa para techos despegados. Uso de tela FOAM especial en tono gis original.</p>
            <span class="badge bg-label-success">Techos</span>
          </div>
        </div>
      </div>
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
                <p class="mb-0 text-muted">Calle Ficticia del Motor 123,<br>Nave 4, Localidad, Madrid.</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="d-flex flex-column align-items-center text-center">
                <div class="avatar avatar-md mb-3">
                  <span class="avatar-initial rounded bg-label-success"><i class="ri-phone-line fs-3"></i></span>
                </div>
                <h6 class="mb-1">Llámanos</h6>
                <p class="mb-0 text-muted">+34 900 000 000<br>Lun-Vie: 8:00 a 18:00</p>
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
