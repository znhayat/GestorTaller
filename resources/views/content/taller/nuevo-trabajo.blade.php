@extends('layouts/contentNavbarLayout')

@section('content')
<style>
  .step-indicator { 
      display: flex; 
      justify-content: space-between; 
      margin-bottom: 2.5rem; 
      position: relative;
      padding: 0 10%;
  }
  .step-indicator::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 10%;
      right: 10%;
      height: 3px;
      background: #e2e8f0;
      z-index: 1;
      transform: translateY(-50%);
      border-radius: 5px;
  }
  .step-item { 
      z-index: 2; 
      border-radius: 50%; 
      width: 50px; 
      height: 50px; 
      display: flex; 
      align-items: center; 
      justify-content: center; 
      background: #e2e8f0; 
      color: #64748b; 
      font-weight: bold; 
      font-size: 1.3rem;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
      border: 4px solid #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
  }
  .step-item.active { 
      background: #696cff; 
      color: #fff; 
      box-shadow: 0 0 0 0.35rem rgba(105, 108, 255, 0.25); 
      transform: scale(1.1);
  }
  .step-item.completed { 
      background: #71dd37; 
      color: #fff; 
      border-color: #fff;
  }
  .wizard-title {
      text-align: center;
      margin-top: 15px;
      font-size: 0.9rem;
      color: #566a7f;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
  }
  .wizard-step {
      animation: fadeIn 0.4s ease-out forwards;
  }
  @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
  }
  .card-body.pt-5 {
      padding: 3rem 4rem !important;
  }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="ri-add-circle-line me-2"></i> Nuevo Trabajo</h4>
    <a href="{{ route('encargos.recepcion') }}" class="btn btn-secondary"><i class="ri-arrow-left-line me-1"></i> Volver a Recepción</a>
  </div>

  <div class="card">
    <div class="card-body pt-5">
      
      <!-- Indicador de Pasos -->
      <div class="step-indicator">
        <div>
           <div class="step-item active" id="indicator-1"><i class="ri-user-line"></i></div>
           <div class="wizard-title">Cliente</div>
        </div>
        <div>
           <div class="step-item" id="indicator-2"><i class="ri-car-line"></i></div>
           <div class="wizard-title">Vehículo</div>
        </div>
        <div>
           <div class="step-item" id="indicator-3"><i class="ri-tools-line"></i></div>
           <div class="wizard-title">Trabajo</div>
        </div>
        <div>
           <div class="step-item" id="indicator-4"><i class="ri-calendar-line"></i></div>
           <div class="wizard-title">Cita & Info</div>
        </div>
      </div>

      <form method="POST" action="{{ route('trabajo.store') }}" id="wizard-form">
        @csrf

        <!-- PASO 1: DATOS DEL CLIENTE -->
        <div id="step-1" class="wizard-step">
            <h5 class="mb-4 text-primary"><i class="ri-user-line me-2"></i> Paso 1: Datos del Cliente</h5>
            <div class="row">
              <div class="col-md-6 mb-3">
                  <label class="form-label" for="trabajo-nombre">Nombre</label>
                  <input type="text" id="trabajo-nombre" name="nombre" class="form-control" placeholder="P. ej. Juan" required>
              </div>
              <div class="col-md-6 mb-3">
                  <label class="form-label" for="trabajo-apellido">Apellido</label>
                  <input type="text" id="trabajo-apellido" name="apellido" class="form-control" placeholder="P. ej. Pérez" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                  <label class="form-label" for="trabajo-telefono">Teléfono</label>
                  <input type="text" id="trabajo-telefono" name="telefono" class="form-control" placeholder="P. ej. 600123456" required>
              </div>
              <div class="col-md-6 mb-3">
                  <label class="form-label" for="trabajo-correo">Correo Electrónico</label>
                  <input type="email" id="trabajo-correo" name="correo" class="form-control" placeholder="correo@ejemplo.com" required>
              </div>
            </div>
        </div>

        <!-- PASO 2: DATOS DEL VEHÍCULO -->
        <div id="step-2" class="wizard-step d-none">
            <h5 class="mb-4 text-primary"><i class="ri-car-line me-2"></i> Paso 2: Datos del Vehículo</h5>
            <div class="row">
              <div class="col-md-6 mb-4">
                  <label class="form-label" for="trabajo-marca">Marca</label>
                  <input type="text" id="trabajo-marca" name="marca" class="form-control" placeholder="P. ej. Audi" required>
              </div>
              <div class="col-md-6 mb-4">
                  <label class="form-label" for="trabajo-modelo">Modelo</label>
                  <input type="text" id="trabajo-modelo" name="modelo" class="form-control" placeholder="P. ej. A4" required>
              </div>
            </div>
        </div>

        <!-- PASO 3: TIPO DE TRABAJO -->
        <div id="step-3" class="wizard-step d-none">
            <h5 class="mb-4 text-primary"><i class="ri-tools-line me-2"></i> Paso 3: Tipo de Trabajo a Realizar</h5>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label" for="categoria-trabajo">Categoría</label>
                <select id="categoria-trabajo" class="form-select">
                  <option value="">-- Selecciona una categoría --</option>
                  <!-- Llenado por JS -->
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label" for="opcion-trabajo">Trabajo Específico</label>
                <select id="opcion-trabajo" class="form-select" disabled>
                  <option value="">-- Selecciona primero la categoría --</option>
                </select>
              </div>
            </div>
            
            <div class="mb-3 mt-2">
                <label class="form-label" for="trabajo-descripcion">Descripción para el Taller</label>
                <textarea id="trabajo-descripcion" name="descripcion" class="form-control" rows="4" required placeholder="Aquí aparecerá tu selección y puedes añadir detalles extras..."></textarea>
                <div class="form-text text-muted">Esta información aparecerá en la tarjeta del Kanban de Producción y Recepción.</div>
            </div>
        </div>

        <!-- PASO 4: CITA Y PRESUPUESTO -->
        <div id="step-4" class="wizard-step d-none">
            <h5 class="mb-4 text-primary fw-bold"><i class="ri-calendar-line me-2"></i> Paso 4: Cita Previa y Presupuesto Base</h5>
            
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="ri-information-line me-2 fs-4"></i>
                <div>
                   Al guardar, este trabajo aparecerá automáticamente en el <strong>Kanban de Recepción</strong> bajo la columna "Cita Agendada". Se moverá mediante las reglas del sistema a "En revisión" en la fecha de la cita hasta que el presupuesto sea Aceptado.
                </div>
            </div>

            <h6 class="mb-3 fw-bold mt-4">Fecha y Hora de la Cita de Revisión</h6>
            <div class="row">
              <div class="col-md-6 mb-3">
                  <label class="form-label" for="trabajo-fecha">Fecha</label>
                  <input type="date" id="trabajo-fecha" name="cita_revision" class="form-control" value="{{ date('Y-m-d', strtotime('+1 days')) }}" required>
              </div>
              <div class="col-md-6 mb-3">
                  <label class="form-label" for="trabajo-hora">Hora</label>
                  <input type="time" id="trabajo-hora" name="hora_cita" class="form-control" value="09:00" required>
              </div>
            </div>

            <!-- Panel de disponibilidad de la Agenda -->
            <div class="card bg-lighter mb-4 border shadow-none">
              <div class="card-body p-3">
                 <h6 class="card-title fw-bold text-secondary mb-2"><i class="ri-calendar-event-line me-1"></i> Agenda para el día seleccionado</h6>
                 <div id="agenda-preview-container" class="small text-muted">
                    Cargando disponibilidad...
                 </div>
              </div>
            </div>

            <h6 class="mb-3 fw-bold mt-4 text-primary">Presupuesto y Tiempo</h6>
            <div class="row">
              <div class="col-md-6 mb-3">
                  <label class="form-label" for="trabajo-materiales">Gasto Materiales Estimado (€)</label>
                  <input type="number" id="trabajo-materiales" step="0.01" name="precio_materiales" class="form-control" value="0" required>
              </div>
              <div class="col-md-6 mb-3">
                  <label class="form-label" for="trabajo-horas">Tiempo estimado de trabajo (Horas)</label>
                  <input type="number" id="trabajo-horas" step="0.5" name="precio_horas" class="form-control" value="0" required>
                  <div class="form-text">Cantidad de horas previstas para esta reparación.</div>
              </div>
            </div>
        </div>

        <!-- BOTONERA WIZARD -->
        <hr class="mt-4 mb-4">
        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary d-none px-4" id="btn-prev"><i class="ri-arrow-left-line me-1"></i> Anterior</button>
            <div class="ms-auto">
                <button type="button" class="btn btn-primary px-4" id="btn-next">Siguiente <i class="ri-arrow-right-line ms-1"></i></button>
                <button type="submit" class="btn btn-success d-none px-4" id="btn-submit"><i class="ri-save-line me-1"></i> Crear Trabajo</button>
            </div>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ---- LÓGICA DE TRABAJOS DINÁMICOS ----
    const trabajosCatalog = {
      "TAPIZADO DE PUERTAS": [
        "Tapizado completo de panel de puerta",
        "Tapizado de inserto central (solo la parte de tela/cuero)",
        "Tapizado de reposabrazos de puerta",
        "Tapizado de bolsillo de puerta"
      ],
      "TAPIZADO DE TECHO": [
        "Tapizado completo de cielo raso",
        "Tapizado de pilares (A, B, C)",
        "Tapizado de viseras solares",
        "Tapizado de manijas de agarre"
      ],
      "TAPIZADO DE VOLANTE": [
        "Tapizado completo del volante",
        "Tapizado deportivo (con perforaciones)",
        "Tapizado bitono (dos colores)",
        "Tapizado con diseño ergonómico (resaltes para dedos)"
      ],
      "TAPIZADO DE PALANCA DE CAMBIOS": [
        "Tapizado de pomo de palanca",
        "Tapizado de fuelle (funda acordeón)",
        "Tapizado de base de palanca"
      ],
      "TAPIZADO DE ASIENTOS": [
        "Tapizado completo de asientos delanteros",
        "Tapizado completo de asientos traseros",
        "Tapizado solo de posacabezas",
        "Tapizado solo de apoyabrazos central",
        "Tapizado con diseño diamantado (cuadros)",
        "Tapizado bitono (dos colores en el mismo asiento)"
      ],
      "OTROS TAPIZADOS": [
        "Tapizado de consola central",
        "Tapizado de alfombras",
        "Tapizado de baúl (maletero)",
        "Tapizado de tablero (dashboard)"
      ]
    };

    const catSelect = document.getElementById('categoria-trabajo');
    const optSelect = document.getElementById('opcion-trabajo');
    const descTextarea = document.getElementById('trabajo-descripcion');

    // Poblar Categorías
    for (const cat in trabajosCatalog) {
        catSelect.add(new Option(cat, cat));
    }

    catSelect.addEventListener('change', function() {
        optSelect.innerHTML = '<option value="">-- Selecciona un trabajo --</option>';
        if (this.value) {
            optSelect.disabled = false;
            trabajosCatalog[this.value].forEach(op => {
                optSelect.add(new Option(op, op));
            });
        } else {
            optSelect.disabled = true;
        }
    });

    optSelect.addEventListener('change', function() {
        if(catSelect.value && optSelect.value) {
            descTextarea.value = `Categoría: ${catSelect.value}\nTrabajo a realizar: ${optSelect.value}\n\nDetalles adicionales: `;
        }
    });

    // ---- LÓGICA DE WIZARD ----
    let currentStep = 1;
    const totalSteps = 4;
    
    const btnNext = document.getElementById('btn-next');
    const btnPrev = document.getElementById('btn-prev');
    const btnSubmit = document.getElementById('btn-submit');
    
    function showStep(step) {
        // Ocultar todos
        document.querySelectorAll('.wizard-step').forEach(el => el.classList.add('d-none'));
        // Mostrar actual
        document.getElementById('step-' + step).classList.remove('d-none');
        
        // Actualizar Indicadores
        document.querySelectorAll('.step-item').forEach((el, index) => {
            el.classList.remove('active', 'completed');
            if (index + 1 < step) {
                el.classList.add('completed');
                el.innerHTML = '<i class="ri-check-line"></i>'; // Icono de completado
            } else if (index + 1 === step) {
                el.classList.add('active');
                // Restaurar iconos base según el step
                const icons = ['user-line', 'car-line', 'tools-line', 'calendar-line'];
                el.innerHTML = `<i class="ri-${icons[index]}"></i>`;
            } else {
                const icons = ['user-line', 'car-line', 'tools-line', 'calendar-line'];
                el.innerHTML = `<i class="ri-${icons[index]}"></i>`;
            }
        });

        // Control Botones
        if (step === 1) {
            btnPrev.classList.add('d-none');
        } else {
            btnPrev.classList.remove('d-none');
        }

        if (step === totalSteps) {
            btnNext.classList.add('d-none');
            btnSubmit.classList.remove('d-none');
        } else {
            btnNext.classList.remove('d-none');
            btnSubmit.classList.add('d-none');
        }
    }

    function validateCurrentStep() {
        const currentDiv = document.getElementById('step-' + currentStep);
        const inputs = currentDiv.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!input.checkValidity()) {
                input.reportValidity();
                isValid = false;
            }
        });
        
        // Custom validación para el select (no son technically required por HTML, pero controlamos q no pasen si falta descripcion)
        if (currentStep === 3 && descTextarea.value.trim() === '') {
             descTextarea.setCustomValidity("Debes proporcionar una descripción o seleccionar un trabajo");
             descTextarea.reportValidity();
             isValid = false;
        } else {
             descTextarea.setCustomValidity("");
        }

        return isValid;
    }

    btnNext.addEventListener('click', () => {
        if (validateCurrentStep()) {
            currentStep++;
            showStep(currentStep);
        }
    });

    btnPrev.addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // ---- LÓGICA DE DISPONIBILIDAD DE AGENDA ----
    const fechaInput = document.getElementById('trabajo-fecha');
    const agendaPreview = document.getElementById('agenda-preview-container');

    function checkAvailability() {
        const date = fechaInput.value;
        if (!date) return;
        
        agendaPreview.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Comprobando disponibilidad...';
        
        fetch(`/api/disponibilidad?date=${date}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    agendaPreview.innerHTML = '<span class="text-success fw-bold"><i class="ri-checkbox-circle-line me-1"></i> Todo el día está libre.</span> Puedes elegir cualquier hora.';
                } else {
                    let html = '<span class="text-warning fw-bold mb-2 d-block"><i class="ri-error-warning-line me-1"></i> Horarios ocupados:</span>';
                    html += '<ul class="mb-1 ps-3">';
                    data.forEach(item => {
                        html += `<li><strong>${item.hora}h</strong> - ${item.titulo}</li>`;
                    });
                    html += '</ul><span class="text-success"><i class="ri-information-line me-1"></i> El resto del horario está libre.</span>';
                    agendaPreview.innerHTML = html;
                }
            })
            .catch(err => {
                agendaPreview.innerHTML = '<span class="text-danger">Error al consultar la agenda. Inténtalo de nuevo.</span>';
            });
    }

    fechaInput.addEventListener('change', checkAvailability);
    // Llamada inicial
    checkAvailability();

});
</script>
@endsection