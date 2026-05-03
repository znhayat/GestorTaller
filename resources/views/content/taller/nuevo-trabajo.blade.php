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

  /* NAVEGACIÓN DE TEXTO PURO MATERIO */
  .step-indicator { display: flex; justify-content: space-around; margin-bottom: 2.5rem; border-bottom: 1px solid #eee; }
  .step-item { padding: 1rem 0; cursor: pointer; text-align: center; flex: 1; transition: all 0.2s; border-bottom: 2px solid transparent; }
  .step-item:hover { color: #9055FD; }
  .step-item.active { border-bottom: 2px solid #9055FD; }
  .step-item.active .step-label { color: #9055FD; font-weight: 700; }
  .step-label { font-size: 0.85rem; font-weight: 500; color: #999; text-transform: uppercase; }
  .step-item.completed .step-label { color: #555; }
  .step-item.completed::after { content: " \ea10"; font-family: "remixicon"; margin-left: 5px; color: #50cd89; }
  
  .card { border: 1px solid #eff2f5; box-shadow: none; border-radius: 0.75rem; }
  .bg-formal { background-color: #1e1e2d !important; color: #ffffff !important; }
  .bg-light-grey { background-color: #f5f8fa !important; }
  
  .category-card { border: 1px solid #eff2f5; transition: all 0.2s; }
  .category-card:hover { border-color: #0095e8; background: #ffffff; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
  
  .precio-total-input { background-color: transparent !important; font-size: 2.2rem !important; border: none !important; border-bottom: 3px solid #0095e8 !important; border-radius: 0 !important; color: #1e1e2d !important; font-weight: 800 !important; }
  
  .btn-delete-service { background: transparent; color: #f1416c; border: 1px solid #f1416c; padding: 0.4rem; border-radius: 0.4rem; transition: all 0.2s; }
  .btn-delete-service:hover { background: #f1416c; color: #fff; }

  /* Validaciones visuales */
  .is-invalid-phone { border-color: #f1416c !important; }
  .phone-feedback { color: #f1416c; font-size: 0.75rem; font-weight: 600; margin-top: 0.25rem; }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="ri-add-circle-line me-2"></i> Nuevo Trabajo</h4>
    <a href="{{ route('encargos.recepcion') }}" class="btn btn-secondary"><i class="ri-arrow-left-line me-1"></i> Volver a Recepción</a>
  </div>

  <div class="card">
    <div class="card-body pt-5">
      
      <!-- Indicador de Pasos Texto Puro Materio -->
      <div class="step-indicator">
         <div class="step-item active" id="indicator-1" onclick="goToStep(1)">
            <div class="step-label">1. Cliente</div>
         </div>
         <div class="step-item" id="indicator-2" onclick="goToStep(2)">
            <div class="step-label">2. Vehículo</div>
         </div>
         <div class="step-item" id="indicator-3" onclick="goToStep(3)">
            <div class="step-label">3. Servicios</div>
         </div>
         <div class="step-item" id="indicator-4" onclick="goToStep(4)">
            <div class="step-label">4. Resumen</div>
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

                  <!-- Pantalla 2: Opciones de la Categoría -->
                  <div id="panel-opciones" class="d-none">
                      <div class="card border-0 shadow-sm mb-4">
                          <div class="card-header bg-formal py-3 d-flex justify-content-between align-items-center rounded-top">
                              <h6 id="titulo-categoria-seleccionada" class="text-white mb-0 fw-bold"></h6>
                              <button type="button" class="btn btn-sm btn-link text-white p-0 d-flex align-items-center" onclick="volverACategorias()">
                                <small class="me-1">Cerrar</small> <i class="ri-close-line fs-4"></i>
                              </button>
                          </div>
                          <div class="card-body pt-4">
                              <div id="lista-opciones" class="mb-4">
                                  <!-- Se genera por JS (Checkboxes) -->
                              </div>
                              
                              <div class="row g-3">
                                  <div class="col-md-8">
                                      <label class="form-label fw-bold text-muted small uppercase">Anotaciones Técnicas</label>
                                      <input type="text" id="anotacion-servicio" class="form-control" placeholder="Ej: Hilo rojo, piel napa...">
                                  </div>
                                  <div class="col-md-4">
                                      <label class="form-label fw-bold text-primary small uppercase">Precio Servicio (€)</label>
                                      <input type="number" id="precio-individual" class="form-control fw-bold border-primary" value="0" step="0.01">
                                  </div>
                              </div>

                              <div class="d-grid gap-2 mt-4">
                                <button type="button" class="btn btn-primary py-2 fw-bold" id="btn-confirmar-seleccion">
                                    <i class="ri-add-line me-1"></i> Añadir este presupuesto al trabajo
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="volverACategorias()">
                                    <i class="ri-arrow-left-line me-1"></i> Volver a categorías
                                </button>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Panel derecho: Resumen de Servicios (ESTILO PREMIUM) -->
              <div class="col-md-5">
                  <div class="card h-100 border shadow-none">
                    <div class="card-header border-bottom bg-light py-3">
                        <h6 class="mb-0 fw-bold text-dark"><i class="ri-file-list-3-line me-2"></i> RESUMEN DE SERVICIOS</h6>
                    </div>
                    <div class="card-body p-0 carrito-scroll" id="carrito-contenedor">
                        <div id="carrito-vacio" class="text-center py-5">
                            <i class="ri-inbox-archive-line fs-1 text-muted opacity-25"></i>
                            <p class="text-muted small mt-2">No se han añadido servicios todavía</p>
                        </div>
                        <table class="table table-sm table-hover mb-0 d-none" id="tabla-carrito">
                            <thead class="bg-lighter">
                                <tr>
                                    <th class="ps-3 py-2 small">Servicio</th>
                                    <th class="py-2 small text-end">Precio</th>
                                    <th class="py-2 text-center small"></th>
                                </tr>
                            </thead>
                            <tbody id="carrito-lista"></tbody>
                        </table>
                    </div>
                    <div class="card-footer border-top bg-white p-4">
                        <div class="d-flex justify-content-between align-items-center mb-0">
                            <span class="fw-bold text-muted small">TOTAL PRESUPUESTADO</span>
                            <div class="text-end">
                                <span class="fs-2 fw-bolder text-dark d-block" id="txt-total-global">0.00 €</span>
                                <input type="hidden" id="precio-global-step3" value="0">
                            </div>
                        </div>
                    </div>
                  </div>

                  <!-- Textarea oculto para la BD -->
                  <textarea id="trabajo-descripcion" name="descripcion" class="d-none"></textarea>
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
                  <label class="form-label" for="trabajo-hora">Hora de la Cita</label>
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
                    <div class="card h-100 cursor-pointer category-card shadow-none" onclick="seleccionarCategoria('${cat.id}')">
                        <div class="card-body text-center py-4">
                            <i class="${cat.icono} display-5 mb-2 text-formal"></i>
                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.85rem;">${cat.nombre}</h6>
                        </div>
                    </div>
                </div>
            `);
        });
    }

    window.seleccionarCategoria = function(id) {
        categoriaActual = taxonomias.find(c => c.id === id);
        tituloCat.innerHTML = `<i class="${categoriaActual.icono} me-2 text-primary"></i> ${categoriaActual.nombre}`;
        listaOpciones.innerHTML = '';
        categoriaActual.opciones.forEach((opc, idx) => {
            listaOpciones.insertAdjacentHTML('beforeend', `
                <div class="list-group-item d-flex align-items-center py-3">
                    <div class="form-check me-2">
                        <input class="form-check-input service-check" type="checkbox" value="${opc}" id="check-${idx}">
                        <label class="form-check-label w-100 cursor-pointer" for="check-${idx}">
                            <span class="ms-2 fw-medium text-dark">${opc}</span>
                        </label>
                    </div>
                </div>
            `);
        });
        panelCategorias.classList.add('d-none');
        panelOpciones.classList.remove('d-none');
        inputAnotacion.value = '';
    };

    document.getElementById('btn-confirmar-seleccion')?.addEventListener('click', () => {
        const checks = document.querySelectorAll('.service-check:checked');
        const precio = parseFloat(document.getElementById('precio-individual').value) || 0;

        if (checks.length === 0) {
            Swal.fire('Atención', 'Selecciona al menos una opción.', 'warning');
            return;
        }
        
        // Dividimos el precio entre los servicios seleccionados si son varios, 
        // o lo aplicamos al conjunto. Para que sea sencillo, lo aplicamos al conjunto.
        const nombresServicios = Array.from(checks).map(c => c.value).join(', ');
        
        carritoTrabajos.push({
            id: Date.now() + Math.random(),
            categoria: categoriaActual.nombre,
            icono: categoriaActual.icono,
            color: 'primary',
            trabajo: nombresServicios,
            anotacion: document.getElementById('anotacion-servicio').value.trim(),
            precio: precio
        });
        
        renderizarCarrito();
        volverACategorias();
        document.getElementById('precio-individual').value = 0;
    });

    function renderizarCarrito() {
        const tabla = document.getElementById('tabla-carrito');
        const lista = document.getElementById('carrito-lista');
        const vacio = document.getElementById('carrito-vacio');
        
        if (!lista) return;

        if (carritoTrabajos.length === 0) {
            vacio.classList.remove('d-none');
            tabla.classList.add('d-none');
            lista.innerHTML = '';
        } else {
            vacio.classList.add('d-none');
            tabla.classList.remove('d-none');
            lista.innerHTML = '';
            
            carritoTrabajos.forEach(i => {
                lista.insertAdjacentHTML('beforeend', `
                    <tr class="animate__animated animate__fadeIn">
                        <td class="ps-3 py-3">
                            <div class="fw-bold text-dark small">${i.trabajo}</div>
                            <div class="text-muted tiny" style="font-size: 0.7rem;">${i.categoria}${i.anotacion ? ' | ' + i.anotacion : ''}</div>
                        </td>
                        <td class="text-end py-3 fw-bold text-dark">${i.precio.toFixed(2)} €</td>
                        <td class="text-center py-3">
                            <button type="button" class="btn btn-outline-danger btn-sm px-2" onclick="borrarDelCarrito(${i.id})">
                                <i class="ri-delete-bin-line me-1"></i> <span class="small">Eliminar</span>
                            </button>
                        </td>
                    </tr>
                `);
            });
        }
        actualizarTotal();
    }

    function actualizarTotal() {
        const totalCarrito = carritoTrabajos.reduce((sum, item) => sum + item.precio, 0);
        const totalManual = parseFloat(inputPrecioGlobal?.value) || 0;
        const total = totalManual > 0 ? totalManual : totalCarrito;
        
        if (txtTotalGlobal) txtTotalGlobal.textContent = total.toFixed(2) + ' €';
        
        document.getElementById('trabajo-materiales').value = total;
        document.getElementById('trabajo-materiales-display').value = total;
        
        let desc = "";
        carritoTrabajos.forEach(i => {
            desc += `[${i.categoria}] ${i.trabajo} ${i.anotacion ? '('+i.anotacion+')' : ''}\n`;
        });
        if (textareaDescripcion) textareaDescripcion.value = desc;
    }

    window.borrarDelCarrito = function(id) {
        carritoTrabajos = carritoTrabajos.filter(i => i.id !== id);
        renderizarCarrito();
    };

    window.volverACategorias = function() {
        document.getElementById('panel-opciones').classList.add('d-none');
        document.getElementById('panel-categorias').classList.remove('d-none');
    };

    inputPrecioGlobal?.addEventListener('input', actualizarTotal);

    // VALIDACIÓN DE TELÉFONO EN TIEMPO REAL
    const inputTel = document.getElementById('trabajo-telefono');
    inputTel?.addEventListener('input', function() {
        const val = this.value.replace(/\D/g, ''); 
        this.value = val;
        
        if (val.length > 0 && val.length !== 9) {
            this.classList.add('is-invalid-phone');
            if (!document.getElementById('phone-err')) {
                this.insertAdjacentHTML('afterend', '<div id="phone-err" class="phone-feedback">Debe tener exactamente 9 números.</div>');
            }
        } else {
            this.classList.remove('is-invalid-phone');
            document.getElementById('phone-err')?.remove();
        }
    });

    // ---- 3. LÓGICA DEL WIZARD (NAVEGACIÓN) ----
    window.goToStep = function(n) {
        if (n === currentStep) return;
        if (n < currentStep) {
            currentStep = n;
            showStep(n);
        } else {
            // Para ir adelante, validamos el paso actual
            if (validateCurrentStep()) {
                currentStep = n;
                showStep(n);
            }
        }
    };

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
        
        // Validación estándar de HTML5
        inputs.forEach(input => {
            if (!input.checkValidity()) {
                input.reportValidity();
                isValid = false;
            }
        });

        // Validación específica de Teléfono (Paso 1)
        if (currentStep === 1) {
            const telInput = document.getElementById('trabajo-telefono');
            if (telInput.value.length !== 9) {
                telInput.classList.add('is-invalid-phone');
                if (!document.getElementById('phone-err')) {
                    telInput.insertAdjacentHTML('afterend', '<div id="phone-err" class="phone-feedback">Debe tener exactamente 9 números.</div>');
                }
                telInput.focus();
                isValid = false;
            }
        }

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

    inputModelo.addEventListener('focus', function() {
        const q = this.value.trim();
        let url = `${baseUrl}/api/vehiculos/modelos?q=${q}`;
        if (marcaId) url += `&marca_id=${marcaId}`;
        fetch(url).then(res => res.json()).then(data => {
            resModelo.innerHTML = data.map(m => `<div class="search-item" onclick="seleccionarModelo('${m.nombre}')">${m.nombre}</div>`).join('');
            resModelo.classList.toggle('d-none', data.length === 0);
        });
    });

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

    // Disponibilidad de Agenda Unificada
    const fechaInput = document.getElementById('trabajo-fecha');
    const agendaPreview = document.getElementById('agenda-preview-container');
    
    function checkAvailability() {
        if (!fechaInput || !fechaInput.value) return;
        agendaPreview.innerHTML = '<div class="spinner-border spinner-border-sm text-primary"></div> Consultando agenda...';
        
        fetch(`/api/disponibilidad?date=${fechaInput.value}`).then(res => res.json()).then(data => {
            if (data.length === 0) {
                agendaPreview.innerHTML = '<div class="alert alert-success py-2 mb-0"><i class="ri-checkbox-circle-line me-1"></i> No hay citas agendadas para este día.</div>';
            } else {
                let html = '<div class="row g-2 mt-1">';
                data.forEach(i => {
                    const isProd = i.tipo === 'produccion';
                    html += `
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center bg-white border p-2 rounded shadow-none">
                                <span class="badge ${isProd ? 'bg-warning' : 'bg-info'} me-2">${i.hora}</span>
                                <span class="text-truncate small" style="max-width: 80px;">${i.cliente}</span>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                agendaPreview.innerHTML = html;
            }
        });
    }
    fechaInput?.addEventListener('change', checkAvailability);

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