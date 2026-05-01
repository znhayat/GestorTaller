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
  
  /* RESPONSIVE STEPS */
  @media (max-width: 768px) {
      .card-body.pt-5 {
          padding: 1.5rem 1rem !important;
      }
      .step-item span {
          display: none; /* Ocultar texto en móvil */
      }
      .step-item .step-icon {
          margin-right: 0;
          font-size: 1.5rem;
      }
      .step-indicator {
          margin-bottom: 1.5rem;
      }
      .step-item {
          padding: 0.75rem 0.5rem;
      }
      .step-item:not(:last-child)::after {
          display: none; /* Quitar flechas separadoras en móvil */
      }
  }
  /* Estilos para el autocompletado */
  .search-results {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      z-index: 1000;
      background: white;
      border: 1px solid #d9dee3;
      border-radius: 0 0 0.5rem 0.5rem;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
      max-height: 250px;
      overflow-y: auto;
  }
  .search-item {
      padding: 0.75rem 1rem;
      cursor: pointer;
      border-bottom: 1px solid #f0f2f4;
      transition: background 0.2s;
  }
  .search-item:hover {
      background-color: #f8f9fa;
  }
  .search-item:last-child {
      border-bottom: none;
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="text-primary fw-bold mb-0"><i class="ri-user-line me-2"></i> Paso 1: Ficha del Cliente</h5>
                <div class="position-relative" style="width: 300px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-lighter"><i class="ri-search-line"></i></span>
                        <input type="text" id="buscador-cliente" class="form-control" placeholder="Buscar cliente existente...">
                    </div>
                    <div id="resultados-busqueda-cliente" class="search-results d-none"></div>
                </div>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="text" id="trabajo-nombre" name="nombre" class="form-control" placeholder="Nombre" required autocomplete="off">
                    <label for="trabajo-nombre">Nombre</label>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="text" id="trabajo-apellido" name="apellido" class="form-control" placeholder="Apellidos" required autocomplete="off">
                    <label for="trabajo-apellido">Apellidos</label>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="text" id="trabajo-telefono" name="telefono" class="form-control" placeholder="Teléfono" required autocomplete="off">
                    <label for="trabajo-telefono">Teléfono</label>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="email" id="trabajo-correo" name="correo" class="form-control" placeholder="Correo electrónico" required autocomplete="off">
                    <label for="trabajo-correo">Correo electrónico</label>
                  </div>
              </div>
            </div>
        </div>

        <!-- PASO 2: DATOS DEL VEHÍCULO -->
        <div id="step-2" class="wizard-step d-none">
            <h5 class="mb-4 text-primary fw-bold"><i class="ri-car-line me-2"></i> Paso 2: Ficha del Vehículo</h5>
            
            <div id="vehiculos-cliente-container" class="mb-4 d-none">
                <label class="form-label text-muted small fw-bold">VEHÍCULOS DEL CLIENTE</label>
                <div id="lista-vehiculos-cliente" class="d-flex flex-wrap gap-2"></div>
                <hr>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                  <div class="form-floating form-floating-outline position-relative">
                    <input type="text" id="trabajo-marca" name="marca" class="form-control" placeholder="Marca" required autocomplete="off">
                    <label for="trabajo-marca">Marca del vehículo</label>
                    <div id="resultados-busqueda-marca" class="search-results d-none"></div>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-floating form-floating-outline position-relative">
                    <input type="text" id="trabajo-modelo" name="modelo" class="form-control" placeholder="Modelo" required autocomplete="off">
                    <label for="trabajo-modelo">Modelo del vehículo</label>
                    <div id="resultados-busqueda-modelo" class="search-results d-none"></div>
                  </div>
              </div>
            </div>
        </div>

        <!-- PASO 3: SELECCIÓN MÚLTIPLE DE SERVICIOS (ÁRBOL EXPANDIDO) -->
        <div id="step-3" class="wizard-step d-none">
            <h5 class="mb-2 text-primary fw-bold"><i class="ri-tools-line me-2"></i> Paso 3: Selección de Servicios</h5>
            <p class="text-muted mb-3 small">Marca <strong>todos los trabajos</strong> que necesita el vehículo — puedes seleccionar de distintos componentes a la vez.</p>

            <div class="row g-4">
              <!-- Panel izquierdo: árbol de todas las categorías -->
              <div class="col-md-7">
                  <!-- Barra de búsqueda rápida -->
                  <div class="mb-3">
                    <div class="input-group">
                      <span class="input-group-text bg-light border-0"><i class="ri-search-line text-muted"></i></span>
                      <input type="text" id="buscador-servicios" class="form-control bg-light border-0 shadow-sm" placeholder="Buscar tarea... (ej: retapizado, volante...)">
                    </div>
                  </div>

                  <!-- Buscador de Materiales del Almacén (NUEVO) -->
                  <div class="mb-3 position-relative">
                    <div class="input-group">
                      <span class="input-group-text bg-info border-0 text-white"><i class="ri-archive-line"></i></span>
                      <input type="text" id="buscador-materiales-wizard" class="form-control border-info shadow-sm" placeholder="Añadir material del almacén (ej: Piel, Espuma...)">
                    </div>
                    <div id="resultados-materiales-wizard" class="search-results d-none"></div>
                  </div>

                  <!-- Árbol de categorías con checkboxes (siempre expandido) -->
                  <div id="arbol-servicios" class="bg-light rounded p-3 shadow-sm" style="max-height: 380px; overflow-y: auto;">
                    <!-- Generado por JS al cargar -->
                  </div>

                  <!-- Resumen de selección + botón agregar -->
                   <div class="mt-3 p-3 bg-white rounded shadow-sm border">
                    <div class="row g-3 align-items-end">
                      <div class="col-md-8">
                        <label class="form-label small fw-bold text-primary mb-1"><i class="ri-money-dollar-circle-line"></i> Presupuesto Inicial Sugerido (€)</label>
                        <input type="number" id="mat-estimado" class="form-control border-primary text-primary fw-bold" value="0" min="0" step="0.01">
                        <input type="hidden" id="hor-estimado" value="0">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted mb-1"><i class="ri-palette-line"></i> Acabado global</label>
                        <select id="acabado-global" class="form-select">
                          <option value="">Sin especif.</option>
                          <option>Tapizado en piel</option>
                          <option>Tapizado en polipiel</option>
                          <option>Tejido técnico</option>
                          <option>Alcántara / microfibra</option>
                          <option>Piel perforada</option>
                          <option>A definir con cliente</option>
                        </select>
                      </div>
                      <div class="col-12">
                        <textarea id="anotacion-trabajo" class="form-control bg-light border-0" rows="2" placeholder="Anotación especial (opcional): costuras, colores, indicaciones del cliente..."></textarea>
                      </div>
                      <div class="col-12">
                        <button type="button" id="btn-add-trabajo" class="btn btn-primary w-100 fw-bold py-2 mb-2">
                          <i class="ri-add-circle-fill me-1"></i>
                          <span id="btn-add-texto">Selecciona al menos un servicio</span>
                        </button>
                      </div>
                    </div>
                  </div>
              </div>

              <!-- Panel derecho: Carrito de servicios añadidos -->
              <div class="col-md-5 border-start ps-4">
                 <h6 class="fw-bold d-flex justify-content-between align-items-center mb-3 text-secondary">
                    <span>Añadidos al Expediente</span>
                    <span class="badge bg-primary rounded-pill px-3 py-1" id="badge-contador">0</span>
                 </h6>
                 <div class="text-muted small mb-2">
                    <i class="ri-time-line me-1 text-warning"></i><span id="txt-total-horas">0</span>h &nbsp;|&nbsp;
                    <i class="ri-money-dollar-circle-line me-1 text-info"></i><span id="txt-total-mat">0.00</span>€
                 </div>

                 <div id="carrito-vacio" class="text-center py-5 text-muted rounded bg-label-secondary" style="border: 2px dashed #b7c2cc;">
                    <i class="ri-checkbox-multiple-line fs-1 d-block mb-2 opacity-50"></i>
                    Aún no hay servicios.<br><small>Marca los trabajos y pulsa Agregar.</small>
                 </div>

                 <div id="carrito-lista" class="d-flex flex-column gap-2" style="max-height: 380px; overflow-y: auto;">
                    <!-- Se llena vía JS -->
                 </div>

                 <!-- Textarea oculto para la BD -->
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
              <div class="col-md-12 mb-3">
                  <label class="form-label fw-bold text-success fs-5" for="trabajo-materiales"><i class="ri-money-dollar-circle-line me-1"></i> Presupuesto Total del Trabajo (€)</label>
                  <input type="number" id="trabajo-materiales" step="0.01" name="precio_materiales" class="form-control form-control-lg text-success fw-bold border-success" value="0" style="background-color: #fff; font-size: 1.5rem;">
                  <input type="hidden" id="trabajo-horas" name="precio_horas" value="0">
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

    const arbolServicios  = document.getElementById('arbol-servicios');
    const buscador        = document.getElementById('buscador-servicios');
    const descTextarea    = document.getElementById('trabajo-descripcion');
    const btnAdd          = document.getElementById('btn-add-trabajo');
    const btnAddTexto     = document.getElementById('btn-add-texto');
    const anotacionInput  = document.getElementById('anotacion-trabajo');

    // UI Carrito e Inputs de Totales P4
    const contCarritoVacio = document.getElementById('carrito-vacio');
    const divCarritoLista  = document.getElementById('carrito-lista');
    const badgeContador    = document.getElementById('badge-contador');
    const inputTotalMat    = document.getElementById('trabajo-materiales');
    const inputTotalHoras  = document.getElementById('trabajo-horas');
    const txtSumMat        = document.getElementById('txt-total-mat');
    const txtSumHor        = document.getElementById('txt-total-horas');

    let hasManuallyEditedP4 = false;
    inputTotalMat.addEventListener('input', () => hasManuallyEditedP4 = true);
    inputTotalHoras.addEventListener('input', () => hasManuallyEditedP4 = true);

    // ---- GENERAR ÁRBOL COMPLETO DE CATEGORÍAS ----
    // Se renderiza UNA VEZ al cargar la página. Todas las categorías visibles simultáneamente.
    let uidChk = 0;
    for (const cat in taxonomias) {
        const colores = ['primary','success','info','warning','danger','secondary'];
        const colorIdx = Object.keys(taxonomias).indexOf(cat) % colores.length;
        const color = colores[colorIdx];

        // Cabecera de categoría con icono de colapso
        const catId = `cat-${uidChk}`;
        let html = `
            <div class="mb-3 categoria-bloque">
              <div class="d-flex align-items-center mb-2" style="cursor:pointer" onclick="toggleCat('${catId}')">
                <span class="badge bg-${color} me-2 py-1 px-2" style="font-size:0.7rem">${cat.split('(')[0].trim()}</span>
                <small class="text-muted chk-cat-label" data-cat="${cat}"></small>
                <i class="ri-arrow-down-s-line ms-auto text-muted cat-chevron" id="chev-${catId}"></i>
              </div>
              <div id="${catId}" class="ps-3 border-start border-${color} border-2">`;

        taxonomias[cat].trabajos.forEach(tarea => {
            const id = `chk-${uidChk++}`;
            html += `<div class="form-check mb-1 tarea-item">
                <input class="form-check-input chk-tarea" type="checkbox" value="${tarea}" data-cat="${cat}" id="${id}">
                <label class="form-check-label small" for="${id}">${tarea}</label>
              </div>`;
        });

        html += `</div></div>`;
        arbolServicios.insertAdjacentHTML('beforeend', html);
    }

    // Escuchar cambios en todos los checkboxes
    arbolServicios.querySelectorAll('.chk-tarea').forEach(c => c.addEventListener('change', actualizarBotonAgregar));
    actualizarBotonAgregar();

    // Colapsar/expandir categoría
    window.toggleCat = function(id) {
        const el = document.getElementById(id);
        const chev = document.getElementById('chev-' + id);
        if (el.style.display === 'none') {
            el.style.display = 'block';
            chev.className = 'ri-arrow-down-s-line ms-auto text-muted cat-chevron';
        } else {
            el.style.display = 'none';
            chev.className = 'ri-arrow-right-s-line ms-auto text-muted cat-chevron';
        }
    };

    // Buscador en tiempo real
    buscador.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        arbolServicios.querySelectorAll('.tarea-item').forEach(item => {
            const label = item.querySelector('label').textContent.toLowerCase();
            item.style.display = (!q || label.includes(q)) ? 'block' : 'none';
        });
        // Mostrar/ocultar bloques de categoría enteros
        arbolServicios.querySelectorAll('.categoria-bloque').forEach(bloque => {
            const visible = Array.from(bloque.querySelectorAll('.tarea-item')).some(i => i.style.display !== 'none');
            bloque.style.display = visible ? 'block' : 'none';
        });
    });

    function actualizarBotonAgregar() {
        const n = arbolServicios.querySelectorAll('.chk-tarea:checked').length;
        // Actualizar etiquetas de conteo por categoría
        document.querySelectorAll('.chk-cat-label').forEach(lbl => {
            const cat = lbl.dataset.cat;
            const nCat = arbolServicios.querySelectorAll(`.chk-tarea[data-cat="${cat}"]:checked`).length;
            lbl.textContent = nCat > 0 ? `${nCat} seleccionada${nCat > 1 ? 's' : ''}` : '';
        });
        if (n === 0) {
            btnAddTexto.textContent = 'Selecciona al menos un servicio';
            btnAdd.classList.remove('btn-primary');
            btnAdd.classList.add('btn-secondary');
        } else {
            btnAddTexto.textContent = `Agregar ${n} servicio${n > 1 ? 's' : ''} al Expediente`;
            btnAdd.classList.remove('btn-secondary');
            btnAdd.classList.add('btn-primary');
        }
    }

    // ---- AÑADIR AL CARRITO (TODAS LAS MARCADAS) ----
    btnAdd.addEventListener('click', () => {
        const seleccionadas = Array.from(arbolServicios.querySelectorAll('.chk-tarea:checked'));
        if (seleccionadas.length === 0) {
            Swal.fire('Atención', 'Marca al menos un servicio antes de agregar.', 'warning'); return;
        }
        const hTotal  = parseFloat(document.getElementById('hor-estimado').value) || 0;
        const mTotal  = parseFloat(document.getElementById('mat-estimado').value) || 0;
        const nota    = anotacionInput.value.trim();
        const acabado = document.getElementById('acabado-global').value;
        const n       = seleccionadas.length;
        const hPorTarea = Math.round((hTotal / n) * 2) / 2;
        const mPorTarea = parseFloat((mTotal / n).toFixed(2));

        seleccionadas.forEach(chk => {
            carritoTrabajos.push({
                id: Date.now() + Math.random(),
                categoria: chk.dataset.cat,
                trabajo:   chk.value,
                subopcion: acabado,
                anotacion: nota,
                horas:     hPorTarea,
                mat:       mPorTarea
            });
            chk.checked = false; // Desmarcar tras agregar
        });

        // Reset campos
        document.getElementById('hor-estimado').value = 0;
        document.getElementById('mat-estimado').value = 0;
        document.getElementById('acabado-global').value = '';
        anotacionInput.value = '';
        buscador.value = '';
        buscador.dispatchEvent(new Event('input')); // Limpiar filtro
        hasManuallyEditedP4 = false;
        actualizarBotonAgregar();
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
                           <span class="badge bg-label-success"><i class="ri-money-dollar-circle-line"></i> ${item.mat.toFixed(2)}€</span>
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
        
        let sumTotal = 0;
        let textoMarkdown = "## RESUMEN DE SERVICIOS SELECCIONADOS\n---\n";
        
        carritoTrabajos.forEach((item, index) => {
            sumTotal += item.mat;
            
            textoMarkdown += `### Trab. #${index + 1}: ${item.categoria}\n`;
            textoMarkdown += `- **Tarea principal:** ${item.trabajo}\n`;
            if(item.subopcion) textoMarkdown += `- **Tipo/Acabado:** ${item.subopcion}\n`;
            if(item.anotacion) textoMarkdown += `> **Especificación del Taller:** ${item.anotacion}\n`;
            textoMarkdown += `\n`; // Quitamos el desglose de horas y materiales individuales
        });

        // Aplicamos matemáticas a HTML
        txtSumMat.textContent = sumTotal.toFixed(2);
        txtSumHor.parentElement.classList.add('d-none'); // Ocultamos la etiqueta de horas
        
        // Solo sobreescribimos los campos de presupuesto P4 si el operario no los ha borrado/sobreescrito manualmente
        if(!hasManuallyEditedP4) {
            inputTotalMat.value = sumTotal.toFixed(2);
            inputTotalHoras.value = 0; // Siempre 0 horas por defecto ahora
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
    
    window.showStep = function(step) {
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

        // Foco automático
        setTimeout(() => {
            const stepEl = document.getElementById('step-' + step);
            if (stepEl) {
                const firstInput = stepEl.querySelector('input:not([type=hidden]):not([readonly]), select, textarea');
                if (firstInput) firstInput.focus();
            }
        }, 300);
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

    // ---- LÓGICA DE BÚSQUEDA DE CLIENTES ----
    const buscadorCliente = document.getElementById('buscador-cliente');
    const resultadosCliente = document.getElementById('resultados-busqueda-cliente');
    let clientesEncontrados = [];

    buscadorCliente.addEventListener('input', function() {
        const q = this.value.trim();
        if (q.length < 3) {
            resultadosCliente.classList.add('d-none');
            return;
        }

        fetch(`/api/clientes/buscar?q=${q}`)
            .then(res => res.json())
            .then(data => {
                clientesEncontrados = data;
                if (data.length === 0) {
                    resultadosCliente.innerHTML = '<div class="search-item text-muted">No se encontraron clientes</div>';
                } else {
                    let html = '';
                    data.forEach((c, index) => {
                        html += `
                            <div class="search-item" onclick="seleccionarClientePorIndice(${index})">
                                <strong>${c.nombre} ${c.apellido}</strong><br>
                                <small class="text-muted">${c.telefono} | ${c.correo}</small>
                            </div>
                        `;
                    });
                    resultadosCliente.innerHTML = html;
                }
                resultadosCliente.classList.remove('d-none');
            });
    });

    window.seleccionarClientePorIndice = function(index) {
        const cliente = clientesEncontrados[index];
        if (!cliente) return;
        
        seleccionarCliente(cliente);
    };

    window.seleccionarCliente = function(cliente) {
        document.getElementById('trabajo-nombre').value = cliente.nombre;
        document.getElementById('trabajo-apellido').value = cliente.apellido;
        document.getElementById('trabajo-telefono').value = cliente.telefono;
        document.getElementById('trabajo-correo').value = cliente.correo;
        
        buscadorCliente.value = `${cliente.nombre} ${cliente.apellido}`;
        resultadosCliente.classList.add('d-none');
        
        // Cargar sus vehículos para el Paso 2
        const vehContainer = document.getElementById('vehiculos-cliente-container');
        const vehLista = document.getElementById('lista-vehiculos-cliente');
        
        if (cliente.vehiculos && cliente.vehiculos.length > 0) {
            let html = '';
            cliente.vehiculos.forEach(v => {
                html += `
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="seleccionarVehiculo('${v.marca}', '${v.modelo}')">
                        <i class="ri-car-line me-1"></i> ${v.marca} ${v.modelo}
                    </button>
                `;
            });
            vehLista.innerHTML = html;
            vehContainer.classList.remove('d-none');
        } else {
            vehContainer.classList.add('d-none');
        }
        
        // Efecto visual de "rellenado"
        const inputs = ['trabajo-nombre', 'trabajo-apellido', 'trabajo-telefono', 'trabajo-correo'];
        inputs.forEach(id => {
            const el = document.getElementById(id);
            el.classList.add('is-valid');
            setTimeout(() => el.classList.remove('is-valid'), 2000);
        });
    };

    window.seleccionarVehiculo = function(marca, modelo) {
        document.getElementById('trabajo-marca').value = marca;
        document.getElementById('trabajo-modelo').value = modelo;
        
        // Efecto visual
        const inputs = ['trabajo-marca', 'trabajo-modelo'];
        inputs.forEach(id => {
            const el = document.getElementById(id);
            el.classList.add('is-valid');
            setTimeout(() => el.classList.remove('is-valid'), 2000);
        });

        // Pasar automáticamente al siguiente paso tras un breve delay
        setTimeout(() => {
            btnNext.click();
        }, 300);
    };

    // ---- LÓGICA DE BÚSQUEDA DE MATERIALES ----
    const buscadorMateriales = document.getElementById('buscador-materiales-wizard');
    const resultadosMateriales = document.getElementById('resultados-materiales-wizard');

    buscadorMateriales.addEventListener('input', function() {
        const q = this.value.trim();
        if (q.length < 2) {
            resultadosMateriales.classList.add('d-none');
            return;
        }

        fetch(`/api/materiales/buscar?q=${q}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    resultadosMateriales.innerHTML = '<div class="search-item text-muted">No se encontraron materiales</div>';
                } else {
                    let html = '';
                    data.forEach(m => {
                        html += `
                            <div class="search-item d-flex justify-content-between align-items-center" onclick='seleccionarMaterialWizard(${JSON.stringify(m)})'>
                                <div>
                                    <strong>${m.nombre}</strong><br>
                                    <small class="text-muted">${m.tipo} | ${m.precio_unitario}€ / ${m.unidad}</small>
                                </div>
                                <span class="badge bg-label-info">${parseFloat(m.stock)} disp.</span>
                            </div>
                        `;
                    });
                    resultadosMateriales.innerHTML = html;
                }
                resultadosMateriales.classList.remove('d-none');
            });
    });

    window.seleccionarMaterialWizard = function(material) {
        resultadosMateriales.classList.add('d-none');
        buscadorMateriales.value = '';

        Swal.fire({
            title: `Añadir ${material.nombre}`,
            text: `¿Qué cantidad de ${material.unidad} vas a usar? (Precio: ${material.precio_unitario}€/${material.unidad})`,
            input: 'number',
            inputAttributes: {
                min: 0.1,
                step: 0.1
            },
            inputValue: 1,
            showCancelButton: true,
            confirmButtonText: 'Añadir al presupuesto',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed && result.value > 0) {
                const qty = parseFloat(result.value);
                const totalMat = parseFloat((qty * material.precio_unitario).toFixed(2));

                carritoTrabajos.push({
                    id: Date.now() + Math.random(),
                    isMaterial: true,
                    categoria: 'MATERIAL: ' + material.tipo,
                    trabajo:   material.nombre,
                    subopcion: `Cantidad: ${qty} ${material.unidad}`,
                    anotacion: `Precio unitario: ${material.precio_unitario}€`,
                    horas:     0,
                    mat:       totalMat
                });

                renderizarCarrito();
                
                // Animación de éxito
                const badge = document.getElementById('badge-contador');
                badge.classList.add('animate__animated', 'animate__bounce');
                setTimeout(() => badge.classList.remove('animate__animated', 'animate__bounce'), 1000);
            }
        });
    };

    // ---- LÓGICA DE BÚSQUEDA DE MARCAS Y MODELOS ----
    const inputMarca = document.getElementById('trabajo-marca');
    const inputModelo = document.getElementById('trabajo-modelo');
    const resultadosMarca = document.getElementById('resultados-busqueda-marca');
    const resultadosModelo = document.getElementById('resultados-busqueda-modelo');
    let marcaSeleccionadaId = null;

    inputMarca.addEventListener('input', function() {
        const q = this.value.trim();
        if (q.length < 1) {
            resultadosMarca.classList.add('d-none');
            return;
        }

        fetch(`/api/vehiculos/marcas?q=${q}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    resultadosMarca.innerHTML = '<div class="search-item text-muted">No encontrada (escríbela libremente)</div>';
                } else {
                    let html = '';
                    data.forEach(m => {
                        html += `<div class="search-item" onclick="seleccionarMarca(${m.id}, '${m.nombre}')">${m.nombre}</div>`;
                    });
                    resultadosMarca.innerHTML = html;
                }
                resultadosMarca.classList.remove('d-none');
            });
    });

    window.seleccionarMarca = function(id, nombre) {
        inputMarca.value = nombre;
        marcaSeleccionadaId = id;
        resultadosMarca.classList.add('d-none');
        inputModelo.focus();
        // Limpiamos modelo al cambiar marca
        inputModelo.value = '';
    };

    inputModelo.addEventListener('focus', function() {
        if (this.value.trim() === '') {
            buscarModelos('');
        }
    });

    inputModelo.addEventListener('input', function() {
        buscarModelos(this.value.trim());
    });

    function buscarModelos(q) {
        let url = `/api/vehiculos/modelos?q=${q}`;
        if (marcaSeleccionadaId) url += `&marca_id=${marcaSeleccionadaId}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    resultadosModelo.innerHTML = '<div class="search-item text-muted">No encontrado</div>';
                } else {
                    let html = '';
                    data.forEach(m => {
                        html += `<div class="search-item" onclick="seleccionarModelo('${m.nombre}')">${m.nombre}</div>`;
                    });
                    resultadosModelo.innerHTML = html;
                }
                resultadosModelo.classList.remove('d-none');
            });
    }

    window.seleccionarModelo = function(nombre) {
        inputModelo.value = nombre;
        resultadosModelo.classList.add('d-none');
    };

    // Cerrar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!buscadorCliente.contains(e.target) && !resultadosCliente.contains(e.target)) {
            resultadosCliente.classList.add('d-none');
        }
        if (!buscadorMateriales.contains(e.target) && !resultadosMateriales.contains(e.target)) {
            resultadosMateriales.classList.add('d-none');
        }
        if (!inputMarca.contains(e.target) && !resultadosMarca.contains(e.target)) {
            resultadosMarca.classList.add('d-none');
        }
        if (!inputModelo.contains(e.target) && !resultadosModelo.contains(e.target)) {
            resultadosModelo.classList.add('d-none');
        }
    });

    // ---- ATAJOS DE TECLADO Y FOCO ----

    // Navegación con Enter
    document.getElementById('wizard-form').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            // Evitar que el Enter envíe el formulario si no estamos en el último paso
            if (currentStep < totalSteps) {
                // Si estamos en un textarea, dejamos que haga el salto de línea
                if (e.target.tagName === 'TEXTAREA') return;
                
                e.preventDefault();
                btnNext.click();
            } else if (currentStep === totalSteps) {
                // En el último paso, dejamos que el Enter (o Ctrl+Enter) envíe el formulario
                // pero solo si no estamos en un textarea o si pulsamos Ctrl
                if (e.target.tagName === 'TEXTAREA' && !e.ctrlKey) return;
            }
        }
    });

    // ---- MEJORA DE UX: AUTO-SELECCIONAR NÚMEROS AL HACER FOCO ----
    // Esto evita tener que borrar el "0" manualmente
    document.getElementById('wizard-form').addEventListener('focus', function(e) {
        if (e.target.tagName === 'INPUT' && e.target.type === 'number') {
            e.target.select();
        }
    }, true);

});
</script>
@endsection