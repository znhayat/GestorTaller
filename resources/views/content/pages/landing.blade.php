@extends('layouts/blankLayout')

@section('title', 'Zana Tapicería - Especialistas en Tapicería de Automóviles en Girona')

@section('content')
<style>
    /* Identidad: ROJO PREMIUM */
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

    /* Header Superior */
    .top-header {
        background: #fff;
        border-bottom: 1px solid #eee;
        padding: 10px 0;
    }

    .contact-link {
        text-decoration: none;
        color: #444;
        font-weight: 600;
        font-size: 0.9rem;
        transition: 0.3s;
    }

    .contact-link:hover {
        color: #d32f2f;
    }

    /* Hero Section */
    .hero-section {
        padding: 100px 0;
        background: #fff;
        position: relative;
        overflow: hidden;
    }

    .hero-title {
        font-weight: 800;
        font-size: 3.5rem;
        line-height: 1.1;
        color: #d32f2f;
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
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 40px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
    }

    /* Grid de Servicios Detallados */
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

    /* Botón Flotante de LLAMADA */
    .phone-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #d32f2f;
        color: #fff;
        width: 60px;
        height: 60px;
        border-radius: 50px;
        text-align: center;
        font-size: 30px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .phone-float:hover {
        transform: scale(1.1);
        color: #fff;
        background: #9a0007;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
    }
</style>

<!-- Top Header -->
<header class="top-header sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="{{ asset('assets/img/zana-logo.jpg') }}?v={{ time() }}" alt="Zana Tapicería" height="110">
        </a>
        <div class="d-none d-lg-flex gap-4 align-items-center">
            <a href="tel:631498980" class="contact-link"><i class="ri-phone-fill me-1"></i> 631 498 980</a>
            <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill px-3 py-1 small">Acceso Personal</a>
            <a href="tel:631498980" class="btn btn-primary rounded-pill px-4 text-white">Llamar para Presupuesto</a>
        </div>
        <div class="d-lg-none d-flex gap-2">
            <a href="{{ route('login') }}" class="btn btn-outline-dark btn-icon rounded-pill"><i
                    class="ri-user-settings-line"></i></a>
            <a href="tel:631498980" class="btn btn-primary btn-icon rounded-pill text-white"><i class="ri-phone-fill"></i></a>
        </div>
    </div>
</header>

<!-- Hero Section -->
<section class="hero-section" style="background: url('{{ asset('assets/img/FondoLanding.jpg') }}?v={{ time() }}') no-repeat;">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="hero-glass">
                    <span class="badge bg-label-danger mb-3 px-3 py-2 rounded-pill fw-bold">TAPIZADOS DE AUTOMÓVILES</span>
                    <p class="lead text-muted mb-4">Especialistas en la restauración y personalización de tapicerías de coches. En
                        Vilanna (Bescanó), devolvemos el esplendor original a tu vehículo.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="tel:631498980" class="btn btn-primary btn-lg rounded-pill px-4 text-white">Llamar ahora</a>
                        <a href="#galeria" class="btn btn-outline-dark btn-lg rounded-pill px-4">Ver trabajos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Grid de Servicios Detallados -->
<section id="servicios" class="bg-light py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold fs-1">Nuestros Servicios</h2>
        </div>
        <div class="row g-3">
            @php
            $servicios = [
            ['t' => 'Volantes', 'd' => 'Tactos impecables y costuras perfectas.', 'img' => 'volantes.png'],
            ['t' => 'Techos', 'd' => 'Retapizado de techos caídos o dañados.', 'img' => 'techos.jpg'],
            ['t' => 'Puertas', 'd' => 'Paneles y apoyabrazos con estilo.', 'img' => 'puertas.jpg'],
            ['t' => 'Asientos', 'd' => 'Confort y estética a tu medida.', 'img' => 'asientos.png'],
            ['t' => 'Pomocambios', 'd' => 'Detalles que marcan la diferencia.', 'img' => 'pomocambios.jpg'],
            ['t' => 'Interiores', 'd' => 'Transformación integral de la cabina.', 'img' => 'interiores.jpg'],
            ];
            @endphp
            @foreach ($servicios as $s)
            <div class="col-md-4 col-sm-6">
                <a href="tel:631498980" class="service-grid-item"
                    style="background-image: url('{{ asset('assets/img/' . $s['img']) }}?v={{ time() }}');">
                    <div class="service-grid-overlay"></div>
                    <div class="service-grid-content">
                        <h4 class="fw-bold text-white mb-1">{{ $s['t'] }}</h4>
                        <p class="small mb-0 opacity-75">{{ $s['d'] }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Portfolio -->
<section id="galeria" class="py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold fs-1">Trabajos Realizados</h2>
        </div>
        <div class="row g-4">
            @forelse($fotos->take(6) as $foto)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm overflow-hidden rounded-4 h-100">
                    @if ($foto->tipo === 'antes' && $foto->despues)
                    {{-- Diseño Comparativo (Dos fotos) --}}
                    <div class="d-flex" style="height: 250px;">
                        <div class="w-50 position-relative border-end border-white border-2">
                            <img src="{{ asset('storage/' . $foto->ruta) }}?v={{ time() }}" class="w-100 h-100" style="object-fit: cover;"
                                alt="Antes">
                            <span class="badge bg-warning position-absolute bottom-0 start-0 m-2"
                                style="font-size: 0.6rem;">ANTES</span>
                        </div>
                        <div class="w-50 position-relative">
                            <img src="{{ asset('storage/' . $foto->despues->ruta) }}?v={{ time() }}" class="w-100 h-100"
                                style="object-fit: cover;" alt="Después">
                            <span class="badge bg-success position-absolute bottom-0 end-0 m-2"
                                style="font-size: 0.6rem;">DESPUÉS</span>
                        </div>
                    </div>
                    @else
                    {{-- Diseño Foto Única --}}
                    <img src="{{ asset('storage/' . $foto->ruta) }}?v={{ time() }}" class="card-img-top"
                        style="height: 250px; object-fit: cover;" alt="Trabajo">
                    @endif

                    <div class="card-body text-center">
                        <h6 class="fw-bold mb-1">{{ $foto->titulo_galeria }}</h6>
                        <div class="mb-2">
                            <span class="badge bg-label-danger">{{ $foto->categoria_texto }}</span>
                        </div>
                        @if ($foto->descripcion)
                        <p class="small text-muted mb-0">{{ Str::limit($foto->descripcion, 80) }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center text-muted w-100">Pronto subiremos nuevos trabajos.</p>
            @endforelse
        </div>
        <div class="text-center mt-5">
            <a href="tel:631498980" class="btn btn-primary btn-lg rounded-pill px-5 text-white">¿Quieres un resultado así?
                Llámanos</a>
        </div>
    </div>
</section>

<!-- Ubicación y Contacto -->
<section id="contacto" class="py-5 bg-dark text-white">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0 text-center text-md-start">
                <h2 class="fw-bold display-5 text-white mb-4">¿Hablamos de tu coche?</h2>
                <p class="fs-5 opacity-75 mb-4">Llámanos ahora para obtener un presupuesto personalizado sin compromiso.</p>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <i class="ri-map-pin-2-fill text-primary fs-3 me-3"></i>
                        <span>Vilanna, Bescanó (Girona)</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <i class="ri-phone-fill text-primary fs-3 me-3"></i>
                        <span class="fs-4 fw-bold">631 498 980</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <i class="ri-mail-fill text-primary fs-3 me-3"></i>
                        <span>tapecero65@gmail.com</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <div class="p-5 bg-white rounded-4 shadow-lg">
                    <h4 class="text-dark fw-bold mb-4">Presupuesto Telefónico</h4>
                    <a href="tel:631498980" class="btn btn-primary btn-lg w-100 rounded-pill text-white py-3 shadow">
                        <i class="ri-phone-fill me-2"></i> Llamar al 631 498 980
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="py-4 bg-black text-white border-top border-secondary">
    <div class="container text-center text-md-between d-md-flex align-items-center">
        <p class="opacity-50 mb-0 small">© {{ date('Y') }} Zana Tapicería Automotriz.</p>
        <div class="d-flex justify-content-center gap-3 mt-3 mt-md-0">
            <a href="{{ route('login') }}"
                class="text-white opacity-75 text-decoration-none small fw-bold">Administración</a>
            <a href="#" class="text-white opacity-50 text-decoration-none small">Aviso Legal</a>
            <a href="#" class="text-white opacity-50 text-decoration-none small">Privacidad</a>
        </div>
    </div>
</footer>

<!-- Teléfono Flotante (Sustituye a WhatsApp) -->
<a href="tel:631498980" class="phone-float" title="Llamar ahora">
    <i class="ri-phone-fill"></i>
</a>

@endsection