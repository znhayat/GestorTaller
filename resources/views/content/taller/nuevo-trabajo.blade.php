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
          padding: 1.25rem 0.75rem !important;
      }
      .step-item span {
          display: none;
      }
      .step-item .step-icon {
          margin-right: 0;
          font-size: 1.4rem;
      }
      .step-indicator {
          margin-bottom: 1.25rem;
          border-radius: 0.3rem;
      }
      .step-item {
          padding: 0.6rem 0.3rem;
      }
      .step-item:not(:last-child)::after {
          display: none;
      }
      /* Ajuste de títulos en móvil */
      h5.text-primary {
          font-size: 1.1rem;
      }
      /* Buscador en móvil */
      .position-relative[style*="width: 300px"] {
          width: 100% !important;
          margin-top: 10px;
      }
      .d-flex.justify-content-between.align-items-center.mb-4 {
          flex-direction: column;
          align-items: flex-start !important;
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

  /* CLASES DE LIMPIEZA */
  .search-worker-width { width: 300px; }
  .carrito-scroll { min-height: 250px; max-height: 400px; overflow-y: auto; }
  .precio-total-input { background-color: #fff !important; font-size: 1.5rem !important; }
  .cursor-pointer { cursor: pointer; }
  .hover-shadow:hover { box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1) !important; }
  .transition-all { transition: all 0.3s ease; }
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
                <div class="position-relative search-worker-width">
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
                    <label for="trabajo-marca">Marca del Vehículo</label>
                    <div id="resultados-busqueda-marca" class="search-results d-none"></div>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-floating form-floating-outline position-relative">
                    <input type="text" id="trabajo-modelo" name="modelo" class="form-control" placeholder="Modelo" required autocomplete="off">
                    <label for="trabajo-modelo">Modelo del Vehículo</label>
                    <div id="resultados-busqueda-modelo" class="search-results d-none"></div>
                  </div>
              </div>
            </div>
        </div>

        <!-- PASO 3: SELECCIÓN DE SERVICIOS (CONFIGURADOR VISUAL) -->
        <div id="step-3" class="wizard-step d-none">
            <h5 class="mb-2 text-primary fw-bold"><i class="ri-layout-grid-fill me-2"></i> Paso 3: ¿Qué vamos a hacer?</h5>
            <p class="text-muted mb-4 small">Selecciona una categoría para ver las opciones disponibles.</p>

            <div class="row g-4">
              <!-- Panel izquierdo: Selector Visual -->
              <div class="col-md-7">
                  <!-- Pantalla 1: Grid de Categorías -->
                  <div id="panel-categorias" class="row g-3">
                      <!-- Se genera por JS -->
                  </div>

                  <!-- Pantalla 2: Opciones de la Categoría (Oculto por defecto) -->
                  <div id="panel-opciones" class="d-none animate__animated animate__fadeIn">
                      <button type="button" class="btn btn-sm btn-outline-secondary mb-3" onclick="volverACategorias()">
                        <i class="ri-arrow-left-line me-1"></i> Volver a categorías
                      </button>
                      <h6 id="titulo-categoria-seleccionada" class="fw-bold text-dark mb-3"></h6>
                      <div id="lista-opciones" class="list-group mb-3 shadow-sm">
                          <!-- Se genera por JS -->
                      </div>
                      
                      <!-- Campo de Notas Rápidas -->
                      <div class="card bg-lighter border shadow-none mb-3">
                        <div class="card-body p-3">
                            <label class="form-label fw-bold small text-muted"><i class="ri-edit-2-line me-1"></i> Notas para este servicio</label>
                            <input type="text" id="anotacion-servicio" class="form-control form-control-sm" placeholder="Ej: Hilo rojo, cuero perforado, etc.">
                        </div>
                      </div>

                      <button type="button" class="btn btn-primary w-100 py-2" id="btn-confirmar-seleccion">
                        <i class="ri-add-line me-1"></i> Añadir al Presupuesto
                      </button>
                  </div>
              </div>

              <!-- Panel derecho: Resumen del Trabajo -->
              <div class="col-md-5">
                  <div class="card h-100 shadow-none border">
                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark small"><i class="ri-shopping-cart-2-line me-1"></i> SERVICIOS AÑADIDOS</span>
                        <span id="badge-contador" class="badge rounded-pill bg-primary">0</span>
                    </div>
                    <div class="card-body p-0 carrito-scroll" id="carrito-contenedor">
                        <div id="carrito-vacio" class="text-center py-5 text-muted">
                            <i class="ri-inbox-line fs-1 d-block mb-2 opacity-50"></i>
                            <small>No hay servicios todavía</small>
                        </div>
                        <div id="carrito-lista" class="p-2"></div>
                    </div>
                    <div class="card-footer bg-light p-3 border-top">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="small text-muted">Precio Estimado:</span>
                            <span class="fw-bold text-primary" id="txt-total-global">0.00 €</span>
                        </div>
                        <div class="form-floating form-floating-outline mb-0">
                          <input type="number" id="precio-global-step3" class="form-control fw-bold text-primary" placeholder="0.00" value="0">
                          <label for="precio-global-step3">Precio Total Global (€)</label>
                        </div>
                    </div>
                  </div>

                  <!-- Textarea oculto para la BD -->
                  <textarea id="trabajo-descripcion" name="descripcion" class="d-none"></textarea>
                  <input type="hidden" id="trabajo-materiales" name="precio_materiales" value="0">
                  <input type="hidden" id="trabajo-horas" name="precio_horas" value="0">
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
                 <h6 class="mb-3 fw-bold mt-5 text-primary"><i class="ri-wallet-3-line me-2"></i> Presupuesto Estimado y Tiempo Base</h6>
                 <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                    <i class="ri-magic-line me-3 fs-4"></i>
                    <div class="small">
                        Basado en la suma del carrito. <strong>Si desconoces algún importe, puedes dejarlo a 0 y concertarlo más adelante con el cliente</strong>, o puedes editar este total manualmente si deseas hacer una tarifa plana global.
                    </div>
                 </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-12 mb-3">
                  <label class="form-label fw-bold text-success fs-5" for="trabajo-materiales-display"><i class="ri-money-dollar-circle-line me-1"></i> Presupuesto Total del Trabajo (€)</label>
                  <input type="number" id="trabajo-materiales-display" step="0.01" class="form-control form-control-lg text-success fw-bold border-success precio-total-input" value="0" readonly>
                  <input type="hidden" id="trabajo-materiales" name="precio_materiales" value="0">
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

      <script>
document.addEventListener('DOMContentLoaded', function() {
    // ---- 1. CONFIGURACIÓN Y DATOS ----
    const baseUrl = "{{ url('/') }}";
    const taxonomias = [
        { id: 'asientos', nombre: 'Asientos', icono: 'ri-sofa-line', color: 'primary', opciones: ['Retapizado integral', 'Reparar orejeras', 'Espumado / Mullido', 'Reparar quemadura'] },
        { id: 'techo', nombre: 'Techo / Cielo', icono: 'ri-arrow-up-circle-line', color: 'danger', opciones: ['Retapizado de techo', 'Techo solar / Cortinilla', 'Pilares (A, B, C)', 'Parasoles'] },
        { id: 'puertas', nombre: 'Puertas', icono: 'ri-door-line', color: 'success', opciones: ['Paneles completos', 'Apoyabrazos puerta', 'Inserciones tela/piel'] },
        { id: 'volante', nombre: 'Volante / Cambio', icono: 'ri-steering-fill', color: 'info', opciones: ['Retapizar volante', 'Pomo de cambio', 'Fuelle de cambio', 'Freno de mano'] },
        { id: 'suelo', nombre: 'Suelo / Alfombras', icono: 'ri-grid-line', color: 'warning', opciones: ['Moqueta completa', 'Alfombrillas a medida', 'Insonorización suelo'] },
        { id: 'otros', nombre: 'Otros / Especiales', icono: 'ri-more-line', color: 'secondary', opciones: ['Bandeja trasera', 'Salpicadero', 'Maletero', 'Capota (Cabrio)', 'Bordado personalizado'] }
    ];

    let carritoTrabajos = [];
    let categoriaActual = null;
    let opcionSeleccionada = null;
    let currentStep = 1;
    const totalSteps = 4;

    // Elementos UI
    const panelCategorias = document.getElementById('panel-categorias');
    const panelOpciones = document.getElementById('panel-opciones');
    const listaOpciones = document.getElementById('lista-opciones');
    const tituloCat = document.getElementById('titulo-categoria-seleccionada');
    const inputAnotacion = document.getElementById('anotacion-servicio');
    const carritoLista = document.getElementById('carrito-lista');
    const carritoVacio = document.getElementById('carrito-vacio');
    const badgeContador = document.getElementById('badge-contador');
    const txtTotalGlobal = document.getElementById('txt-total-global');
    const inputPrecioGlobal = document.getElementById('precio-global-step3');
    const textareaDescripcion = document.getElementById('trabajo-descripcion');
    
    // Botones Wizard
    const btnNext = document.getElementById('btn-next');
    const btnPrev = document.getElementById('btn-prev');
    const btnSubmit = document.getElementById('btn-submit');

    // ---- 2. LÓGICA DEL CONFIGURADOR VISUAL (PASO 3) ----
    function renderCategorias() {
        if (!panelCategorias) return;
        panelCategorias.innerHTML = '';
        taxonomias.forEach(cat => {
            panelCategorias.insertAdjacentHTML('beforeend', `
                <div class="col-6 col-sm-4">
                    <div class="card h-100 cursor-pointer border shadow-none hover-shadow transition-all" onclick="seleccionarCategoria('${cat.id}')">
                        <div class="card-body text-center py-4 bg-label-${cat.color}">
                            <i class="${cat.icono} display-5 mb-2 text-${cat.color}"></i>
                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.85rem;">${cat.nombre}</h6>
                        </div>
                    </div>
                </div>
            `);
        });
        panelCategorias.insertAdjacentHTML('beforeend', `
            <div class="col-6 col-sm-4">
                <div class="card h-100 cursor-pointer border border-dashed shadow-none" onclick="servicioPersonalizado()">
                    <div class="card-body text-center py-4 bg-label-secondary">
                        <i class="ri-add-line display-5 mb-2 text-secondary"></i>
                        <h6 class="mb-0 fw-bold text-muted" style="font-size: 0.85rem;">Personalizado</h6>
                    </div>
                </div>
            </div>
        `);
    }

    window.seleccionarCategoria = function(id) {
        categoriaActual = taxonomias.find(c => c.id === id);
        tituloCat.innerHTML = `<i class="${categoriaActual.icono} me-2 text-${categoriaActual.color}"></i> ${categoriaActual.nombre}`;
        listaOpciones.innerHTML = '';
        categoriaActual.opciones.forEach(opc => {
            listaOpciones.insertAdjacentHTML('beforeend', `
                <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3" onclick="seleccionarOpcion(this, '${opc}')">
                    <span>${opc}</span>
                    <i class="ri-arrow-right-s-line text-muted"></i>
                </button>
            `);
        });
        panelCategorias.classList.add('d-none');
        panelOpciones.classList.remove('d-none');
        inputAnotacion.value = '';
        opcionSeleccionada = null;
    };

    window.volverACategorias = function() {
        panelOpciones.classList.add('d-none');
        panelCategorias.classList.remove('d-none');
    };

    window.seleccionarOpcion = function(btn, opc) {
        listaOpciones.querySelectorAll('.list-group-item').forEach(i => i.classList.remove('active', 'bg-primary', 'text-white'));
        btn.classList.add('active', 'bg-primary', 'text-white');
        opcionSeleccionada = opc;
    };

    window.servicioPersonalizado = async function() {
        const { value: text } = await Swal.fire({
            title: 'Servicio Personalizado',
            input: 'text',
            inputLabel: '¿Qué vamos a hacer?',
            placeholder: 'Escribe el trabajo aquí...',
            showCancelButton: true
        });
        if (text) {
            categoriaActual = { nombre: 'Especial', icono: 'ri-star-line', color: 'secondary' };
            opcionSeleccionada = text;
            añadirAlCarrito();
        }
    };

    document.getElementById('btn-confirmar-seleccion')?.addEventListener('click', () => {
        if (!opcionSeleccionada) {
            Swal.fire('Atención', 'Selecciona una opción antes de continuar.', 'warning');
            return;
        }
        añadirAlCarrito();
    });

    function añadirAlCarrito() {
        const nota = inputAnotacion.value.trim();
        carritoTrabajos.push({
            id: Date.now(),
            categoria: categoriaActual.nombre,
            icono: categoriaActual.icono,
            color: categoriaActual.color,
            trabajo: opcionSeleccionada,
            anotacion: nota
        });
        renderizarCarrito();
        volverACategorias();
    }

    window.borrarDelCarrito = function(id) {
        carritoTrabajos = carritoTrabajos.filter(i => i.id !== id);
        renderizarCarrito();
    };

    function renderizarCarrito() {
        if (!carritoLista) return;
        if (carritoTrabajos.length === 0) {
            carritoVacio.classList.remove('d-none');
            carritoLista.innerHTML = '';
            badgeContador.textContent = '0';
        } else {
            carritoVacio.classList.add('d-none');
            carritoLista.innerHTML = '';
            carritoTrabajos.forEach(i => {
                carritoLista.insertAdjacentHTML('beforeend', `
                    <div class="card border mb-2 shadow-none animate__animated animate__fadeInRight">
                        <div class="card-body p-2 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="p-2 rounded me-3 bg-label-${i.color}">
                                    <i class="${i.icono} text-${i.color}"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 small fw-bold text-dark">${i.trabajo}</h6>
                                    <small class="text-muted">${i.categoria}${i.anotacion ? ' • ' + i.anotacion : ''}</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm text-danger" onclick="borrarDelCarrito(${i.id})"><i class="ri-delete-bin-line"></i></button>
                        </div>
                    </div>
                `);
            });
            badgeContador.textContent = carritoTrabajos.length;
        }
        actualizarTotal();
    }

    function actualizarTotal() {
        const total = parseFloat(inputPrecioGlobal.value) || 0;
        if (txtTotalGlobal) txtTotalGlobal.textContent = total.toFixed(2) + ' €';
        document.getElementById('trabajo-materiales').value = total;
        
        let desc = "";
        carritoTrabajos.forEach(i => {
            desc += `[${i.categoria}] ${i.trabajo} ${i.anotacion ? '('+i.anotacion+')' : ''}\n`;
        });
        if (textareaDescripcion) textareaDescripcion.value = desc;
    }

    inputPrecioGlobal?.addEventListener('input', actualizarTotal);

    // ---- 3. LÓGICA DEL WIZARD (NAVEGACIÓN) ----
    window.showStep = function(step) {
        document.querySelectorAll('.wizard-step').forEach(el => el.classList.add('d-none'));
        document.getElementById('step-' + step).classList.remove('d-none');
        
        document.querySelectorAll('.step-item').forEach((el, index) => {
            el.classList.remove('active', 'completed');
            if (index + 1 < step) el.classList.add('completed');
            else if (index + 1 === step) el.classList.add('active');
        });

        btnPrev.classList.toggle('d-none', step === 1);
        if (step === totalSteps) {
            btnNext.classList.add('d-none');
            btnSubmit.classList.remove('d-none');
        } else {
            btnNext.classList.remove('d-none');
            btnSubmit.classList.add('d-none');
        }
        
        // Foco automático
        setTimeout(() => {
            const firstInput = document.getElementById('step-' + step).querySelector('input:not([type=hidden]):not([readonly]), select, textarea');
            if (firstInput) firstInput.focus();
        }, 300);
    };

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

        if (currentStep === 3 && carritoTrabajos.length === 0) {
             Swal.fire('Atención', 'Añade al menos un trabajo para continuar.', 'warning');
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

    // ---- 4. BÚSQUEDAS API (CLIENTES, VEHÍCULOS, MATERIALES) ----
    const buscadorCliente = document.getElementById('buscador-cliente');
    const resultadosCliente = document.getElementById('resultados-busqueda-cliente');
    let clientesEncontrados = [];

    buscadorCliente.addEventListener('input', function() {
        const q = this.value.trim();
        if (q.length < 3) {
            resultadosCliente.classList.add('d-none');
            return;
        }
        fetch(`${baseUrl}/api/clientes/buscar?q=${q}`)
            .then(res => res.json())
            .then(data => {
                clientesEncontrados = data;
                if (data.length === 0) {
                    resultadosCliente.innerHTML = '<div class="search-item text-muted">No se encontraron clientes</div>';
                } else {
                    let html = '';
                    data.forEach((c, index) => {
                        html += `<div class="search-item" onclick="seleccionarClientePorIndice(${index})"><strong>${c.nombre} ${c.apellido}</strong><br><small>${c.telefono}</small></div>`;
                    });
                    resultadosCliente.innerHTML = html;
                }
                resultadosCliente.classList.remove('d-none');
            });
    });

    window.seleccionarClientePorIndice = function(index) {
        const c = clientesEncontrados[index];
        if (!c) return;
        document.getElementById('trabajo-nombre').value = c.nombre;
        document.getElementById('trabajo-apellido').value = c.apellido;
        document.getElementById('trabajo-telefono').value = c.telefono;
        document.getElementById('trabajo-correo').value = c.correo;
        buscadorCliente.value = `${c.nombre} ${c.apellido}`;
        resultadosCliente.classList.add('d-none');
        
        const vehContainer = document.getElementById('vehiculos-cliente-container');
        const vehLista = document.getElementById('lista-vehiculos-cliente');
        if (c.vehiculos?.length > 0) {
            vehLista.innerHTML = c.vehiculos.map(v => `
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="seleccionarVehiculo('${v.marca}', '${v.modelo}')">
                    <i class="ri-car-line me-1"></i> ${v.marca} ${v.modelo}
                </button>
            `).join('');
            vehContainer.classList.remove('d-none');
        } else {
            vehContainer.classList.add('d-none');
        }
    };

    window.seleccionarVehiculo = function(marca, modelo) {
        document.getElementById('trabajo-marca').value = marca;
        document.getElementById('trabajo-modelo').value = modelo;
        setTimeout(() => btnNext.click(), 300);
    };

    // Búsqueda de Marcas y Modelos
    const inputMarca = document.getElementById('trabajo-marca');
    const inputModelo = document.getElementById('trabajo-modelo');
    const resMarca = document.getElementById('resultados-busqueda-marca');
    const resModelo = document.getElementById('resultados-busqueda-modelo');
    let marcaId = null;

    inputMarca.addEventListener('input', function() {
        const q = this.value.trim();
        if (q.length < 1) { resMarca.classList.add('d-none'); return; }
        fetch(`${baseUrl}/api/vehiculos/marcas?q=${q}`).then(res => res.json()).then(data => {
            resMarca.innerHTML = data.map(m => `<div class="search-item" onclick="seleccionarMarca(${m.id}, '${m.nombre}')">${m.nombre}</div>`).join('');
            resMarca.classList.toggle('d-none', data.length === 0);
        });
    });

    window.seleccionarMarca = function(id, nombre) {
        inputMarca.value = nombre;
        marcaId = id;
        resMarca.classList.add('d-none');
        inputModelo.focus();
    };

    inputModelo.addEventListener('input', function() {
        const q = this.value.trim();
        let url = `${baseUrl}/api/vehiculos/modelos?q=${q}`;
        if (marcaId) url += `&marca_id=${marcaId}`;
        fetch(url).then(res => res.json()).then(data => {
            resModelo.innerHTML = data.map(m => `<div class="search-item" onclick="seleccionarModelo('${m.nombre}')">${m.nombre}</div>`).join('');
            resModelo.classList.toggle('d-none', data.length === 0);
        });
    });

    window.seleccionarModelo = function(n) { inputModelo.value = n; resModelo.classList.add('d-none'); };

    // Disponibilidad de Agenda
    const fechaInput = document.getElementById('trabajo-fecha');
    const agendaPreview = document.getElementById('agenda-preview-container');
    function checkAvailability() {
        if (!fechaInput.value) return;
        fetch(`/api/disponibilidad?date=${fechaInput.value}`).then(res => res.json()).then(data => {
            if (data.length === 0) agendaPreview.innerHTML = '<span class="text-success fw-bold">Día libre</span>';
            else agendaPreview.innerHTML = `Ocupado: ${data.map(i => i.hora+'h').join(', ')}`;
        });
    }
    fechaInput.addEventListener('change', checkAvailability);

    // Inicialización
    renderCategorias();
    checkAvailability();
    
    // Cerrar resultados clics fuera
    document.addEventListener('click', (e) => {
        if (!buscadorCliente.contains(e.target)) resultadosCliente.classList.add('d-none');
        if (!inputMarca.contains(e.target)) resMarca.classList.add('d-none');
        if (!inputModelo.contains(e.target)) resModelo.classList.add('d-none');
    });

    // Navegación Enter
    document.getElementById('wizard-form').addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
            e.preventDefault();
            if (currentStep < totalSteps) btnNext.click();
        }
    });

    // UX: Auto-seleccionar números
    document.getElementById('wizard-form').addEventListener('focus', function(e) {
        if (e.target.tagName === 'INPUT' && e.target.type === 'number') {
            e.target.select();
        }
    }, true);
});
</script>
@endsection