@extends('layouts/contentNavbarLayout')

@section('content')
<style>
  .step-indicator { 
      display: flex; 
      justify-content: space-between; 
      margin-bottom: 2.5rem; 
      padding: 0;
      background: #f8f9fa;
      border-radius: 0.5rem;
      border: 1px solid #ebedf2;
      overflow: hidden;
  }
  .step-item { 
      flex: 1;
      text-align: center;
      padding: 1rem;
      position: relative;
      color: #a1acb8;
      font-weight: 600;
      font-size: 0.95rem;
      transition: all 0.2s ease-in-out;
      text-transform: uppercase;
      letter-spacing: 0.5px;
  }
  .step-item:not(:last-child)::after {
      content: '\EA6E';
      font-family: "remixicon";
      position: absolute;
      right: -8px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1.5rem;
      color: #d9dee3;
      z-index: 1;
  }
  .step-item.active { 
      color: #696cff; 
      background-color: rgba(105, 108, 255, 0.08);
      border-bottom: 3px solid #696cff;
  }
  .step-item.completed { 
      color: #71dd37; 
      background-color: rgba(113, 221, 55, 0.05);
  }
  .step-item .step-icon {
      font-size: 1.15rem;
      margin-right: 0.4rem;
      vertical-align: middle;
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
      <div class="step-indicator mb-5 shadow-sm">
         <div class="step-item active" id="indicator-1">
            <i class="ri-user-line step-icon"></i> <span>1. Cliente</span>
         </div>
         <div class="step-item" id="indicator-2">
            <i class="ri-car-line step-icon"></i> <span>2. Vehículo</span>
         </div>
         <div class="step-item" id="indicator-3">
            <i class="ri-tools-line step-icon"></i> <span>3. Servicios</span>
         </div>
         <div class="step-item" id="indicator-4">
            <i class="ri-calendar-check-line step-icon"></i> <span>4. Resumen</span>
         </div>
      </div>

      <form method="POST" action="{{ route('trabajo.store') }}" id="wizard-form">
        @csrf

        <!-- PASO 1: DATOS DEL CLIENTE -->
        <div id="step-1" class="wizard-step">
            <h5 class="mb-4 text-primary fw-bold"><i class="ri-user-line me-2"></i> Paso 1: Ficha del Cliente</h5>
            <div class="row g-3">
              <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="text" id="trabajo-nombre" name="nombre" class="form-control" placeholder="Nombre" required>
                    <label for="trabajo-nombre">Nombre</label>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="text" id="trabajo-apellido" name="apellido" class="form-control" placeholder="Apellidos" required>
                    <label for="trabajo-apellido">Apellidos</label>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="text" id="trabajo-telefono" name="telefono" class="form-control" placeholder="Teléfono" required>
                    <label for="trabajo-telefono">Teléfono</label>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="email" id="trabajo-correo" name="correo" class="form-control" placeholder="Correo electrónico" required>
                    <label for="trabajo-correo">Correo electrónico</label>
                  </div>
              </div>
            </div>
        </div>

        <!-- PASO 2: DATOS DEL VEHÍCULO -->
        <div id="step-2" class="wizard-step d-none">
            <h5 class="mb-4 text-primary fw-bold"><i class="ri-car-line me-2"></i> Paso 2: Ficha del Vehículo</h5>
            <div class="row g-3">
              <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="text" id="trabajo-marca" name="marca" class="form-control" placeholder="Marca" required>
                    <label for="trabajo-marca">Marca del vehículo</label>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="text" id="trabajo-modelo" name="modelo" class="form-control" placeholder="Modelo" required>
                    <label for="trabajo-modelo">Modelo del vehículo</label>
                  </div>
              </div>
            </div>
        </div>

        <!-- PASO 3: CARRITO MULTI-SERVICIO -->
        <div id="step-3" class="wizard-step d-none">
            <h5 class="mb-2 text-primary fw-bold"><i class="ri-tools-line me-2"></i> Paso 3: Selección de Servicios</h5>
            <p class="text-muted mb-4 small">Configure los elementos a intervenir. Puede agregar múltiples trabajos a este expediente.</p>
            
            <div class="row">
              <!-- Creador de Tareas -->
              <div class="col-md-6 border-end pe-4">
                  <div class="mb-3">
                    <label class="form-label fw-bold text-dark"><i class="ri-car-fill text-primary"></i> 1. Componente a tapizar</label>
                    <select id="categoria-trabajo" class="form-select form-select-lg bg-light border-0 shadow-sm">
                      <option value="">-- Elija un grupo --</option>
                    </select>
                  </div>
                  
                  <div class="mb-3">
                    <label class="form-label fw-bold text-dark"><i class="ri-file-list-3-line text-primary"></i> 2. Tipo de servicio principal</label>
                    <select id="opcion-trabajo" class="form-select bg-light border-0 shadow-sm" disabled>
                      <option value="">-- Seleccione tarea --</option>
                    </select>
                    <input type="text" id="opcion-trabajo-libre" class="form-control bg-light border-0 shadow-sm d-none mt-2" placeholder="Especifique el trabajo a realizar...">
                  </div>

                  <div class="mb-3">
                    <label class="form-label fw-bold text-dark"><i class="ri-palette-line text-primary"></i> 3. Especificación o Acabado <small class="text-muted fw-normal">(Opcional)</small></label>
                    <select id="subopcion-trabajo" class="form-select bg-light border-0 shadow-sm" disabled>
                      <option value="">-- Variantes de acabado --</option>
                    </select>
                    <input type="text" id="subopcion-trabajo-libre" class="form-control bg-light border-0 shadow-sm d-none mt-2" placeholder="Especifique el material o acabado...">
                  </div>

                  <div class="mb-3">
                    <label class="form-label fw-bold text-dark"><i class="ri-edit-2-line text-primary"></i> Anotación Especial <small class="text-muted fw-normal">(Opcional)</small></label>
                    <textarea id="anotacion-trabajo" class="form-control bg-light border-0 shadow-sm" rows="2" placeholder="Detalles extra, costuras a medida, indicaciones del cliente..."></textarea>
                  </div>

                  <div class="row mt-4">
                    <div class="col-6">
                       <label class="form-label fw-bold text-info"><i class="ri-money-dollar-circle-line"></i> Gastos Mat. (€)</label>
                       <input type="number" id="mat-estimado" class="form-control fw-bold border-info text-info" value="0" min="0" step="0.01">
                    </div>
                    <div class="col-6">
                       <label class="form-label fw-bold text-warning"><i class="ri-time-line"></i> Previsión Horas</label>
                       <input type="number" id="hor-estimado" class="form-control fw-bold border-warning text-warning" value="0" min="0" step="0.5">
                    </div>
                  </div>

                  <div class="mt-4">
                     <button type="button" id="btn-add-trabajo" class="btn btn-primary w-100 fw-bold shadow-sm py-2" disabled>
                        <i class="ri-add-circle-fill me-1"></i> Confirmar y Agregar Tarea
                     </button>
                  </div>
              </div>

              <!-- Cesta de Trabajos (Carrito) -->
              <div class="col-md-6 ps-4">
                 <h6 class="fw-bold d-flex justify-content-between align-items-center mb-3 text-secondary">
                    <span>Trabajos Registrados (<span id="txt-total-horas">0</span>h | <span id="txt-total-mat">0.00</span>€)</span>
                    <span class="badge bg-primary rounded-pill px-3 py-2 fs-6" id="badge-contador">0</span>
                 </h6>
                 
                 <div id="carrito-vacio" class="text-center py-5 text-muted rounded bg-label-secondary" style="border: 2px dashed #b7c2cc;">
                    <i class="ri-shopping-cart-2-line fs-1 d-block mb-2 opacity-50"></i>
                    Aún no hay trabajos.<br><small>Vaya añadiéndolos desde el panel de la izquierda.</small>
                 </div>
                 
                 <div id="carrito-lista" class="d-flex flex-column gap-2 pe-1" style="max-height: 420px; overflow-y: auto;">
                    <!-- Se llena vía JS -->
                 </div>
                 
                 <!-- Generador oculto hacia BD -->
                 <textarea id="trabajo-descripcion" name="descripcion" class="d-none" required></textarea>
              </div>
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

            <h6 class="mb-3 fw-bold mt-5 text-primary"><i class="ri-wallet-3-line me-2"></i> Presupuesto Estimado y Tiempo Base</h6>
            <div class="alert alert-success d-flex align-items-center mb-4" role="alert" style="border-left: 4px solid var(--bs-success);">
                <i class="ri-magic-line me-3 fs-4"></i>
                <div class="small">
                    Basado en la suma del carrito. <strong>Si desconoces algún importe, puedes dejarlo a 0 y concertarlo más adelante con el cliente</strong>, o puedes editar este total manualmente si deseas hacer una tarifa plana global.
                </div>
            </div>
            
            <div class="row">
              <div class="col-md-6 mb-3">
                  <label class="form-label fw-bold text-info" for="trabajo-materiales"><i class="ri-money-dollar-circle-line"></i> Total Material Base (€)</label>
                  <input type="number" id="trabajo-materiales" step="0.01" name="precio_materiales" class="form-control form-control-lg text-info fw-bold" value="0" style="background-color: #fff;">
              </div>
              <div class="col-md-6 mb-3">
                  <label class="form-label fw-bold text-warning" for="trabajo-horas"><i class="ri-time-line"></i> Total Horas Previstas (H)</label>
                  <input type="number" id="trabajo-horas" step="0.5" name="precio_horas" class="form-control form-control-lg text-warning fw-bold" value="0" style="background-color: #fff;">
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
    // ---- LÓGICA DE DICCIONARIO TAPICERÍA AVANZADO ----
    const taxonomias = {
        "Asientos (Butacas)": {
            trabajos: [
                "Retapizado integral de asientos",
                "Retapizado parcial (banqueta / respaldo / orejeras)",
                "Sustitución de espumas (espumado)",
                "Reparación de costuras y paneles"
            ],
            tipos: [
                "Tapizado en piel",
                "Tapizado en polipiel",
                "Tejido técnico",
                "Confección de fundas a medida"
            ]
        },
        "Cielo (Guarnecido techo)": {
            trabajos: [
                "Sustitución de cielo completo",
                "Reparación de cielo descolgado (re-encolado)",
                "Restauración de soporte de techo"
            ],
            tipos: ["Cielo estándar", "Cielo personalizado (alcántara, microfibra, etc.)"]
        },
        "Paneles de puerta": {
            trabajos: ["Retapizado de paneles", "Sustitución de insertos", "Reparación de bases y soportes"],
            tipos: ["Guarnecido completo", "Inserciones tapizadas (centro de puerta)"]
        },
        "Volante": {
            trabajos: ["Retapizado de volante", "Restauración de aro"],
            tipos: ["Piel lisa / perforada", "Costura vista personalizada"]
        },
        "Pomo de cambio": {
            trabajos: ["Retapizado o forrado", "Sustitución de recubrimiento"],
            tipos: ["Acabado en piel", "Personalización de costuras"]
        },
        "Fuelle de cambio/freno": {
            trabajos: ["Confección de fuelles", "Sustitución"],
            tipos: ["Fuelle estándar", "Fuelle personalizado"]
        },
        "Reposabrazos / Consola central": {
            trabajos: ["Retapizado", "Reacondicionado de acolchado"],
            tipos: ["Piel / Tela Estándar"]
        },
        "Moqueta (Revestimiento suelo)": {
            trabajos: ["Sustitución de moqueta completa", "Fabricación de alfombrillas a medida"],
            tipos: ["Calidad origen", "Calidad Premium Perimetrada"]
        },
        "Maletero (Zona de Carga)": {
            trabajos: ["Revestimiento interior", "Moquetado de maletero"],
            tipos: ["Goma resistente", "Moqueta insonorizante"]
        },
        "Otros guarnecidos (Pilares, Bandeja)": {
            trabajos: ["Retapizado de pilares (A, B, C)", "Bandeja trasera", "Sustitución de revestimientos"],
            tipos: ["Igualado de origen", "Estética personalizada"]
        },
        "Otro Trabajo (Personalizado)": {
            trabajos: ["Trabajo especial a medida", "Reparación genérica"],
            tipos: ["A definir por el taller"]
        }
    };

    let carritoTrabajos = [];

    const catSelect = document.getElementById('categoria-trabajo');
    const optSelect = document.getElementById('opcion-trabajo');
    const suboptSelect = document.getElementById('subopcion-trabajo');
    const optSelectLibre = document.getElementById('opcion-trabajo-libre');
    const suboptSelectLibre = document.getElementById('subopcion-trabajo-libre');
    const anotacionInput = document.getElementById('anotacion-trabajo');
    const descTextarea = document.getElementById('trabajo-descripcion');
    const btnAdd = document.getElementById('btn-add-trabajo');
    
    // UI Carrito e Inputs de Totales P4
    const contCarritoVacio = document.getElementById('carrito-vacio');
    const divCarritoLista = document.getElementById('carrito-lista');
    const badgeContador = document.getElementById('badge-contador');
    const inputTotalMat = document.getElementById('trabajo-materiales');
    const inputTotalHoras = document.getElementById('trabajo-horas');
    const txtSumMat = document.getElementById('txt-total-mat');
    const txtSumHor = document.getElementById('txt-total-horas');

    // Estado local para evitar sobreescritura agresiva si cambian manualmente el input total final
    let hasManuallyEditedP4 = false;
    inputTotalMat.addEventListener('input', () => hasManuallyEditedP4 = true);
    inputTotalHoras.addEventListener('input', () => hasManuallyEditedP4 = true);

    // 1. Inyectar catálogo
    for (const cat in taxonomias) {
        catSelect.add(new Option(cat, cat));
    }

    // 2. Comportamientos DOM Selectores
    catSelect.addEventListener('change', function() {
        optSelect.innerHTML = '<option value="">-- Seleccione tarea --</option>';
        suboptSelect.innerHTML = '<option value="">-- Variantes de acabado --</option>';
        optSelect.disabled = true;
        suboptSelect.disabled = true;
        btnAdd.disabled = true;
        
        optSelectLibre.classList.add('d-none');
        suboptSelectLibre.classList.add('d-none');
        optSelectLibre.value = '';
        suboptSelectLibre.value = '';

        if (this.value === "Otro Trabajo (Personalizado)") {
            optSelectLibre.classList.remove('d-none');
            suboptSelectLibre.classList.remove('d-none');
            // Hacerlos directamente obligatorios para desbloquear
            btnAdd.disabled = false; // Se validara en el click
        } else if (this.value && taxonomias[this.value]) {
            optSelect.disabled = false;
            taxonomias[this.value].trabajos.forEach(op => optSelect.add(new Option(op, op)));
            suboptSelect.disabled = false;
            taxonomias[this.value].tipos.forEach(sub => suboptSelect.add(new Option(sub, sub)));
        }
    });

    optSelect.addEventListener('change', () => {
        btnAdd.disabled = optSelect.value === '';
    });
    
    optSelectLibre.addEventListener('input', () => {
        btnAdd.disabled = optSelectLibre.value.trim() === '';
    });

    // 3. Añadir a Carrito
    btnAdd.addEventListener('click', () => {
        const h = parseFloat(document.getElementById('hor-estimado').value) || 0;
        const m = parseFloat(document.getElementById('mat-estimado').value) || 0;
        const nota = anotacionInput.value.trim();
        
        let mTrabajo = catSelect.value === "Otro Trabajo (Personalizado)" ? optSelectLibre.value.trim() : optSelect.value;
        let mAcabado = catSelect.value === "Otro Trabajo (Personalizado)" ? suboptSelectLibre.value.trim() : suboptSelect.value;
        
        if (!mTrabajo) return; // Validación de seguridad

        let nuevoGasto = {
            id: Date.now(),
            categoria: catSelect.value,
            trabajo: mTrabajo,
            subopcion: mAcabado,
            anotacion: nota,
            horas: h,
            mat: m
        };
        carritoTrabajos.push(nuevoGasto);
        
        // Reset Mini-from
        document.getElementById('hor-estimado').value = 0;
        document.getElementById('mat-estimado').value = 0;
        anotacionInput.value = '';
        catSelect.value = '';
        catSelect.dispatchEvent(new Event('change')); // Limpia el resto
        hasManuallyEditedP4 = false; // Reseteamos la confianza porque acaban de añadir un elemento nuevo
        
        renderizarCarrito();
    });

    window.borrarDelCarrito = function(idAEliminar) {
        carritoTrabajos = carritoTrabajos.filter(x => x.id !== idAEliminar);
        renderizarCarrito();
    };

    function renderizarCarrito() {
        if(carritoTrabajos.length === 0) {
            contCarritoVacio.classList.remove('d-none');
            divCarritoLista.innerHTML = '';
            actualizarTextosyPresupuestos();
            return;
        }

        contCarritoVacio.classList.add('d-none');
        divCarritoLista.innerHTML = '';

        carritoTrabajos.forEach(item => {
            let subt = item.subopcion ? `• ${item.subopcion}` : '';
            let notaHtml = item.anotacion ? `<div class="bg-lighter p-2 rounded mt-2 border-start border-3 border-secondary small text-muted"><i class="ri-edit-2-line me-1"></i> ${item.anotacion}</div>` : '';
            
            divCarritoLista.innerHTML += `
               <div class="card shadow-none border bg-white mb-2 pb-0">
                  <div class="card-body p-3 d-flex justify-content-between align-items-center">
                     <div class="w-100 pe-3">
                        <h6 class="mb-1 text-primary fw-bold">${item.categoria}</h6>
                        <p class="mb-1 text-dark fs-6">${item.trabajo}</p>
                        <small class="text-muted d-block mb-2">${subt}</small>
                        <div class="d-flex gap-2">
                           <span class="badge bg-label-info"><i class="ri-money-dollar-circle-line"></i> ${item.mat.toFixed(2)}€ Mat.</span>
                           <span class="badge bg-label-warning"><i class="ri-time-line"></i> ${item.horas}H</span>
                        </div>
                        ${notaHtml}
                     </div>
                     <button type="button" class="btn btn-sm btn-label-danger" onclick="borrarDelCarrito(${item.id})" title="Eliminar servicio">
                        <i class="ri-delete-bin-line me-1"></i> Quitar
                     </button>
                  </div>
               </div>
            `;
        });
        
        actualizarTextosyPresupuestos();
    }

    function actualizarTextosyPresupuestos() {
        badgeContador.textContent = carritoTrabajos.length;
        
        let sumMat = 0;
        let sumHoras = 0;
        let textoMarkdown = "";
        
        carritoTrabajos.forEach((item, index) => {
            sumMat += item.mat;
            sumHoras += item.horas;
            
            textoMarkdown += `### Trab. #${index + 1}: ${item.categoria}\n`;
            textoMarkdown += `- **Tarea principal:** ${item.trabajo}\n`;
            if(item.subopcion) textoMarkdown += `- **Tipo/Acabado:** ${item.subopcion}\n`;
            if(item.anotacion) textoMarkdown += `> **Especificación del Taller:** ${item.anotacion}\n`;
            textoMarkdown += `- *[${item.horas} H previstas | ${item.mat} € en material previsto]*\n\n`;
        });

        // Aplicamos matemáticas a HTML
        txtSumMat.textContent = sumMat.toFixed(2);
        txtSumHor.textContent = sumHoras;
        
        // Solo sobreescribimos los campos de presupuesto P4 si el operario no los ha borrado/sobreescrito manualmente
        if(!hasManuallyEditedP4) {
            inputTotalMat.value = sumMat.toFixed(2);
            inputTotalHoras.value = sumHoras;
        }

        // Metemos al textarea oculto para alimentar DB en formato puro textual
        descTextarea.value = textoMarkdown.trim();
    }

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
                el.querySelector('i').className = 'ri-checkbox-circle-fill step-icon text-success';
            } else if (index + 1 === step) {
                el.classList.add('active');
                const icons = ['user-line', 'car-line', 'tools-line', 'calendar-check-line'];
                el.querySelector('i').className = `ri-${icons[index]} step-icon`;
            } else {
                const icons = ['user-line', 'car-line', 'tools-line', 'calendar-check-line'];
                el.querySelector('i').className = `ri-${icons[index]} step-icon`;
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
        
        // Custom validación para el select: Requerir al menos 1 array en el carrito
        if (currentStep === 3 && carritoTrabajos.length === 0) {
             Swal.fire('Atención', 'Debe añadir al menos un (1) trabajo al carrito para continuar.', 'warning');
             isValid = false;
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