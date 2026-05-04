@extends('layouts/blankLayout')

@section('title', 'Zana Tapicería - Especialistas en Tapicería de Automóviles en Girona')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap');

    body {
        font-family: 'Outfit', sans-serif;
        background-color: #fcfcfc;
    }

    .text-primary {
        color: #d32f2f !important;
    }

    .bg-primary {
        background: linear-gradient(135deg, #d32f2f 0%, #9a0007 100%) !important;
    }

    .btn-primary {
        background-color: #d32f2f;
        border-color: #d32f2f;
    }

    .btn-primary:hover {
        background-color: #9a0007;
        border-color: #9a0007;
    }

    .top-header {
        background: #fff;
        border-bottom: 1px solid #eee;
        padding: 5px 0;
    }

    .hero-section {
        min-height: 600px;
        display: flex;
        align-items: center;
        background-size: cover !important;
        background-position: center !important;
        position: relative;
    }

    .hero-glass {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 40px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
    }

    .service-grid-item {
        position: relative;
        border-radius: 15px;
        height: 250px;
        overflow: hidden;
        background-color: #222;
        background-size: cover;
        background-position: center;
        color: #fff;
        display: flex;
        align-items: flex-end;
        padding: 20px;
        margin-bottom: 20px;
        transition: 0.3s;
        text-decoration: none;
    }

    .service-grid-item:hover {
        transform: scale(1.02);
    }

    .service-grid-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent);
        z-index: 1;
    }

    .service-grid-content {
        z-index: 2;
    }

    .map-container {
        width: 100%;
        height: 300px;
        border-radius: 15px;
        overflow: hidden;
        border: 1px solid #eee;
    }
</style>

<!-- Header Limpio -->
<header class="top-header sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('assets/img/zana-logo.jpg') }}?v={{ time() }}" alt="Zana Tapicería" height="100">
        </a>
        <div>
            <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill px-4">Acceso</a>
        </div>
    </div>
</header>

<!-- Hero -->
<section class="hero-section" style="background: url('{{ asset('assets/img/FondoLanding.jpg') }}?v={{ time() }}') no-repeat;">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="hero-glass">
                    <span class="badge bg-label-danger mb-3 px-3 py-2 rounded-pill fw-bold">TAPIZADOS DE AUTOMÓVILES</span>
                    <h1 class="fw-bold mb-3" style="color: #d32f2f;">Tapiza con nosotros tu vehículo</h1>
                    <p class="lead text-muted mb-4">Devolvemos la vida al interior de tu coche en Vilanna (Bescanó). Calidad artesanal y acabados premium.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#contacto" class="btn btn-primary btn-lg rounded-pill px-4 text-white">Contactar</a>
                        <a href="#galeria" class="btn btn-outline-dark btn-lg rounded-pill px-4">Ver trabajos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Servicios -->
<section id="servicios" class="bg-light py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold fs-1">Lo que hacemos</h2>
        </div>
        <div class="row g-3">
            @php
            $servicios = [
                ['t' => 'Volantes', 'd' => 'Tactos impecables y costuras a mano.', 'img' => 'volantes.png'],
                ['t' => 'Techos', 'd' => 'Retapizado de techos caídos.', 'img' => 'techos.jpg'],
                ['t' => 'Puertas', 'd' => 'Paneles y apoyabrazos personalizados.', 'img' => 'puertas.jpg'],
                ['t' => 'Asientos', 'd' => 'Confort y estética a tu medida.', 'img' => 'asientos.png'],
                ['t' => 'Pomocambios', 'd' => 'Detalles que marcan la diferencia.', 'img' => 'pomocambios.jpg'],
                ['t' => 'Interiores', 'd' => 'Transformación integral de la cabina.', 'img' => 'interiores.jpg'],
            ];
            @endphp
            @foreach ($servicios as $s)
            <div class="col-md-4 col-sm-6">
                <div class="service-grid-item" style="background-image: url('{{ asset('assets/img/' . $s['img']) }}?v={{ time() }}');">
                    <div class="service-grid-overlay"></div>
                    <div class="service-grid-content">
                        <h4 class="fw-bold text-white mb-1">{{ $s['t'] }}</h4>
                        <p class="small mb-0 opacity-75">{{ $s['d'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Galería -->
<section id="galeria" class="py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold fs-1">Nuestros resultados</h2>
        </div>
        <div class="row g-4">
            @forelse($fotos->take(6) as $foto)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm overflow-hidden rounded-4 h-100">
                    @if ($foto->tipo === 'antes' && $foto->despues)
                    <div class="d-flex" style="height: 250px;">
                        <div class="w-50 position-relative border-end border-white border-2">
                            <img src="{{ asset('storage/' . $foto->ruta) }}?v={{ time() }}" class="w-100 h-100" style="object-fit: cover;" alt="Antes">
                            <span class="badge bg-warning position-absolute bottom-0 start-0 m-2" style="font-size: 0.6rem;">ANTES</span>
                        </div>
                        <div class="w-50 position-relative">
                            <img src="{{ asset('storage/' . $foto->despues->ruta) }}?v={{ time() }}" class="w-100 h-100" style="object-fit: cover;" alt="Después">
                            <span class="badge bg-success position-absolute bottom-0 end-0 m-2" style="font-size: 0.6rem;">DESPUÉS</span>
                        </div>
                    </div>
                    @else
                    <img src="{{ asset('storage/' . $foto->ruta) }}?v={{ time() }}" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Trabajo">
                    @endif
                    <div class="card-body text-center">
                        <h6 class="fw-bold mb-1">{{ $foto->titulo_galeria }}</h6>
                        <span class="badge bg-label-danger mb-2">{{ $foto->categoria_texto }}</span>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center text-muted w-100">Pronto subiremos nuevos trabajos.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Única Sección de Contacto -->
<section id="contacto" class="py-5 bg-dark text-white">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-md-10 mx-auto text-center">
                <h2 class="fw-bold display-6 text-white mb-4">¿Hablamos?</h2>
                <p class="fs-5 opacity-75 mb-5">Si quieres un presupuesto o tienes dudas, estamos aquí para ayudarte.</p>
                
                <div class="row g-4">
                    <div class="col-md-4">
                        <i class="ri-phone-fill fs-1 text-primary mb-2 d-block"></i>
                        <small class="d-block opacity-50">Llámanos</small>
                        <span class="fs-4 fw-bold">631 498 980</span>
                    </div>

                    <div class="col-md-4">
                        <i class="ri-mail-fill fs-1 text-primary mb-2 d-block"></i>
                        <small class="d-block opacity-50">Escríbenos</small>
                        <span class="fs-5 fw-bold d-block">tapecero65@gmail.com</span>
                    </div>

                    <div class="col-md-4">
                        <i class="ri-map-pin-2-fill fs-1 text-primary mb-2 d-block"></i>
                        <small class="d-block opacity-50">Ven a vernos</small>
                        <span class="fs-5 fw-bold d-block">Vilanna, Bescanó (Girona)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="py-4 bg-black text-white border-top border-secondary">
    <div class="container text-center text-md-between d-md-flex align-items-center">
        <p class="opacity-50 mb-0 small">© {{ date('Y') }} Zana Tapicería.</p>
        <div class="d-flex justify-content-center gap-3 mt-3 mt-md-0">
            <a href="{{ route('login') }}" class="text-white opacity-75 text-decoration-none small fw-bold">Gestión</a>
            <a href="#" class="text-white opacity-50 text-decoration-none small">Privacidad</a>
        </div>
    </div>
</footer>
@endsection