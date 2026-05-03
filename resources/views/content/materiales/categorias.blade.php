@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container-xxl">
  <div class="d-flex justify-content-between align-items-center py-3">
    <h4 class="fw-bold mb-0">Inventario de Tapicería</h4>
    <a href="{{ route('materiales.create') }}" class="btn btn-primary btn-sm">Añadir Material</a>
  </div>

  <div class="row g-3 mt-2">
    @php
        // Definimos tus categorías oficiales
        $misCategorias = [
            'Tejidos y pieles',
            'Espumas y rellenos',
            'Hilos y sistemas de costura',
            'Elementos metálicos y fijaciones',
            'Adhesivos y selladores',
            'Preparación de superficies'
        ];

        // Mapeamos los conteos reales que vienen del controlador
        $conteos = $categorias->pluck('total', 'tipo')->toArray();
    @endphp

    @foreach($misCategorias as $catName)
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('materiales.index', ['tipo' => $catName]) }}" class="text-decoration-none">
                <div class="card h-100 shadow-none border text-center hover-shadow" style="transition: 0.2s; border-radius: 12px;">
                    <div class="card-body py-4">
                        <h6 class="fw-bold text-dark mb-1">{{ $catName }}</h6>
                        <small class="text-muted">{{ $conteos[$catName] ?? 0 }} productos</small>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
  </div>
</div>

<style>
    .hover-shadow:hover { 
        border-color: var(--bs-primary) !important; 
        background: rgba(var(--bs-primary-rgb), 0.03); 
    }
</style>
@endsection
