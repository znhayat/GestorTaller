@extends('layouts/contentNavbarLayout')

@section('title', 'Nuevo Trabajo - Tapicería')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-12">
      <div class="bs-stepper-header mb-4 d-flex justify-content-center" role="tablist">
        <button type="button" class="btn btn-primary btn-sm" id="btn-step1">1. Cliente</button>
        <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="btn-step2" disabled>2. Vehículo</button>
        <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="btn-step3" disabled>3. Detalles</button>
      </div>

      <div class="card">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Registro de Orden de Trabajo</h5>
        </div>
        <div class="card-body mt-3">
          <form id="wizardForm" action="{{ route('trabajo.store') }}" method="POST">
            @csrf

            <div class="step-content" id="step1">
              <h6 class="text-primary fw-bold">1. Datos del Cliente</h6>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Nombre</label>
                  <input type="text" name="nombre" class="form-control" placeholder="Juan" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Apellido</label>
                  <input type="text" name="apellido" class="form-control" placeholder="Pérez" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Teléfono</label>
                  <input type="tel" name="telefono" class="form-control" placeholder="600 000 000" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Correo Electrónico</label>
                  <input type="email" name="correo" class="form-control" placeholder="ejemplo@correo.com" required>
                </div>
              </div>
              <div class="mt-4 text-end">
                <button type="button" class="btn btn-primary" onclick="nextStep(2)">Siguiente</button>
              </div>
            </div>

            <div class="step-content d-none" id="step2">
              <h6 class="text-primary fw-bold">2. Información del Vehículo</h6>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Marca</label>
                  <input type="text" name="marca" class="form-control" placeholder="Ej: Audi" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Modelo</label>
                  <input type="text" name="modelo" class="form-control" placeholder="Ej: Audi A4">
                </div>
              </div>
              <div class="mt-4">
                <button type="button" class="btn btn-outline-secondary" onclick="nextStep(1)">Atrás</button>
                <button type="button" class="btn btn-primary" onclick="nextStep(3)">Siguiente</button>
              </div>
            </div>

            <div class="step-content d-none" id="step3">
              <h6 class="text-primary fw-bold">3. Descripción del Servicio</h6>
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label">Detalles del trabajo a realizar</label>
                  <textarea name="descripcion" class="form-control" rows="4" placeholder="Ej: Tapizado de volante..."></textarea>
                </div>
              </div>
              <div class="mt-4">
                <button type="button" class="btn btn-outline-secondary" onclick="nextStep(2)">Atrás</button>
                <button type="submit" class="btn btn-success">Finalizar Registro</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function nextStep(step) {
    // Validación básica: comprobar que el paso actual no esté vacío
    const currentStep = document.querySelector('.step-content:not(.d-none)');
    const inputs = currentStep.querySelectorAll('input[required]');

    for (let input of inputs) {
      if (!input.value) {
        alert('Por favor, rellena los campos obligatorios.');
        return;
      }
    }

    // Cambiar vista
    document.querySelectorAll('.step-content').forEach(el => el.classList.add('d-none'));
    document.getElementById('step' + step).classList.remove('d-none');

    // Cambiar estilos de botones
    for (let i = 1; i <= 3; i++) {
      const btn = document.getElementById('btn-step' + i);
      btn.className = (i === step) ? 'btn btn-primary btn-sm ms-2' : 'btn btn-outline-secondary btn-sm ms-2';
      btn.disabled = (i > step);
    }
  }
</script>
@endsection