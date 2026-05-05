@extends('layouts/contentNavbarLayout')

@section('content')
<style>
  /* Estilos para los pasos del wizard */
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
  
  /* Ajustes para móvil */
  @media (max-width: 768px) {
      .card-body.pt-5 {
          padding: 1rem 0.5rem !important;
      }
      .step-indicator {
          margin-bottom: 1.5rem;
          flex-wrap: nowrap;
          overflow-x: auto;
          justify-content: flex-start;
          -webkit-overflow-scrolling: touch;
      }
      .step-item {
          padding: 0.75rem 0.5rem;
          min-width: 80px;
          flex: 0 0 auto;
      }
      .step-label {
          font-size: 0.7rem !important;
          white-space: nowrap;
      }
      .step-item.active {
          background-color: rgba(144, 85, 253, 0.1);
      }
      
      .btn-lg, .btn-primary, .btn-success, .btn-outline-secondary {
          padding: 0.8rem 1rem;
      }

      .col-6.col-sm-4 {
          width: 50% !important;
      }
      
      .form-floating > label {
          font-size: 0.85rem;
      }
      
      #tabla-carrito th:nth-child(2), 
      #tabla-carrito td:nth-child(2) {
          text-align: right;
      }
      .btn-delete-service span {
          display: none; 
      }
  }

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

  .is-invalid-phone { border-color: #f1416c !important; }
  .phone-feedback { color: #f1416c; font-size: 0.75rem; font-weight: 600; margin-top: 0.25rem; }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="ri-add-circle-line me-2"></i> Nuevo Trabajo</h4>
    <a href="{{ route('encargos.recepcion') }}" class="btn btn-secondary"><i class="ri-arrow-left-line me-1"></i> Volver</a>
  </div>

  <div class="card">
    <div class="card-body pt-5">
      
      <!-- Pasos del proceso -->
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

        <!-- Datos del Cliente -->
        <div id="step-1" class="wizard-step">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="text-primary fw-bold mb-0"><i class="ri-user-line me-2"></i> Quién es el cliente?</h5>
                <div class="position-relative search-worker-width">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-lighter"><i class="ri-search-line"></i></span>
                        <input type="text" id="buscador-cliente" class="form-control" placeholder="Buscar por nombre o telf...">
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
                    <input type="email" id="trabajo-correo" name="correo" class="form-control" placeholder="Correo" required autocomplete="off">
                    <label for="trabajo-correo">Email</label>
                  </div>
              </div>
            </div>
        </div>

        <!-- El coche -->
        <div id="step-2" class="wizard-step d-none">
            <h5 class="mb-4 text-primary fw-bold"><i class="ri-car-line me-2"></i> Qué coche trae?</h5>
            
            <div id="vehiculos-cliente-container" class="mb-4 d-none">
                <label class="form-label text-muted small fw-bold">COCHES YA REGISTRADOS</label>
                <div id="lista-vehiculos-cliente" class="d-flex flex-wrap gap-2"></div>
                <hr>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                  <div class="form-floating form-floating-outline position-relative">
                    <input type="text" id="trabajo-marca" name="marca" class="form-control" placeholder="Marca" required autocomplete="off">
                    <label for="trabajo-marca">Marca</label>
                    <div id="resultados-busqueda-marca" class="search-results d-none"></div>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-floating form-floating-outline position-relative">
                    <input type="text" id="trabajo-modelo" name="modelo" class="form-control" placeholder="Modelo" required autocomplete="off">
                    <label for="trabajo-modelo">Modelo</label>
                    <div id="resultados-busqueda-modelo" class="search-results d-none"></div>
                  </div>
              </div>
            </div>
        </div>

        <!-- Lo que hay que hacer -->
        <div id="step-3" class="wizard-step d-none">
            <h5 class="mb-2 text-primary fw-bold"><i class="ri-layout-grid-fill me-2"></i> Qué vamos a tapizar?</h5>
            <p class="text-muted mb-4 small">Elige una categoría para ver opciones.</p>

            <div class="row g-4">
              <div class="col-md-7">
                  <div id="panel-categorias" class="row g-3"></div>

                  <div id="panel-opciones" class="d-none">
                      <div class="card border-0 shadow-sm mb-4">
                          <div class="card-header bg-formal py-3 d-flex justify-content-between align-items-center rounded-top">
                              <h6 id="titulo-categoria-seleccionada" class="text-white mb-0 fw-bold"></h6>
                              <button type="button" class="btn btn-sm btn-link text-white p-0" onclick="volverACategorias()">
                                <i class="ri-close-line fs-4"></i>
                              </button>
                          </div>
                          <div class="card-body pt-4">
                              <div id="lista-opciones" class="mb-4"></div>
                              
                              <div class="row g-3">
                                  <div class="col-md-8">
                                      <label class="form-label fw-bold text-muted small">Notas (hilo, color...)</label>
                                      <input type="text" id="anotacion-servicio" class="form-control" placeholder="Ej: Hilo rojo">
                                  </div>
                                  <div class="col-md-4">
                                      <label class="form-label fw-bold text-primary small">Precio (€)</label>
                                      <input type="number" id="precio-individual" class="form-control fw-bold border-primary" value="0" step="0.01">
                                  </div>
                              </div>

                              <div class="d-grid gap-2 mt-4">
                                <button type="button" class="btn btn-primary py-2 fw-bold" id="btn-confirmar-seleccion">
                                    <i class="ri-add-line me-1"></i> Añadir servicio
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="volverACategorias()">
                                    Volver atrás
                                </button>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="col-md-5">
                  <div class="card h-100 border shadow-none">
                    <div class="card-header border-bottom bg-light py-3">
                        <h6 class="mb-0 fw-bold text-dark">LISTADO DE TRABAJOS</h6>
                    </div>
                    <div class="card-body p-0 carrito-scroll" id="carrito-contenedor">
                        <div id="carrito-vacio" class="text-center py-5">
                            <p class="text-muted small mt-2">Aún no has añadido nada</p>
                        </div>
                        <table class="table table-sm table-hover mb-0 d-none" id="tabla-carrito">
                            <tbody id="carrito-lista"></tbody>
                        </table>
                    </div>
                    <div class="card-footer border-top bg-white p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-muted small">TOTAL</span>
                            <span class="fs-2 fw-bolder text-dark" id="txt-total-global">0.00 €</span>
                            <input type="hidden" id="precio-global-step3" value="0">
                        </div>
                    </div>
                  </div>
                  <textarea id="trabajo-descripcion" name="descripcion" class="d-none"></textarea>
              </div>
            </div>
        </div>

        <!-- Cita y resumen final -->
        <div id="step-4" class="wizard-step d-none">
            <h5 class="mb-4 text-primary fw-bold"><i class="ri-calendar-line me-2"></i> Cuándo viene el coche?</h5>
            
            <div class="row">
              <div class="col-md-6 mb-3">
                  <label class="form-label">Día de la cita</label>
                  <input type="date" id="trabajo-fecha" name="cita_revision" class="form-control" value="{{ date('Y-m-d', strtotime('+1 days')) }}" required>
              </div>
              <div class="col-md-6 mb-3">
                  <label class="form-label">Hora</label>
                  <input type="time" id="trabajo-hora" name="hora_cita" class="form-control" value="09:00" required>
              </div>
            </div>

            <div id="agenda-preview-container" class="small text-muted mb-4"></div>
            
            <div class="row">
              <div class="col-md-12 mb-3">
                  <label class="form-label fw-bold text-success fs-5">Presupuesto Estimado Total (€)</label>
                  <input type="number" id="trabajo-materiales-display" step="0.01" class="form-control form-control-lg text-success fw-bold border-success precio-total-input" value="0" readonly>
                  <input type="hidden" id="trabajo-materiales" name="precio_materiales" value="0">
                  <input type="hidden" id="trabajo-horas" name="precio_horas" value="0">
              </div>
            </div>
        </div>

        <!-- Botones de abajo -->
        <hr class="mt-4 mb-4">
        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary d-none" id="btn-prev">Anterior</button>
            <div class="ms-auto">
                <button type="button" class="btn btn-primary" id="btn-next">Siguiente</button>
                <button type="submit" class="btn btn-success d-none" id="btn-submit">Guardar Trabajo</button>
            </div>
        </div>
      </form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const apiRoutes = {
        clientes: "{{ route('api.clientes.buscar') }}",
        marcas: "{{ route('api.vehiculos.marcas') }}",
        modelos: "{{ route('api.vehiculos.modelos') }}"
    };
    // Categorías de tapicería
    const taxonomias = [
        { id: 'asientos', nombre: 'Asientos', icono: 'ri-sofa-line', opciones: ['Retapizado integral', 'Reparar orejeras', 'Espumas', 'Quemaduras'] },
        { id: 'techo', nombre: 'Techo', icono: 'ri-arrow-up-circle-line', opciones: ['Retapizado techo', 'Techo solar', 'Pilares', 'Parasoles'] },
        { id: 'puertas', nombre: 'Puertas', icono: 'ri-door-line', opciones: ['Paneles', 'Apoyabrazos', 'Inserciones'] },
        { id: 'volante', nombre: 'Volante / Cambio', icono: 'ri-steering-fill', opciones: ['Retapizar volante', 'Pomo', 'Fuelle', 'Freno mano'] },
        { id: 'otros', nombre: 'Otros', icono: 'ri-more-line', opciones: ['Bandeja', 'Salpicadero', 'Maletero', 'Capota', 'Bordados'] }
    ];

    let carritoTrabajos = [];
    let categoriaActual = null;
    let currentStep = 1;

    // Pintamos las categorías
    function renderCategorias() {
        const panel = document.getElementById('panel-categorias');
        if (!panel) return;
        panel.innerHTML = '';
        taxonomias.forEach(cat => {
            panel.insertAdjacentHTML('beforeend', `
                <div class="col-6 col-sm-4">
                    <div class="card h-100 cursor-pointer category-card shadow-none" onclick="seleccionarCategoria('${cat.id}')">
                        <div class="card-body text-center py-4">
                            <i class="${cat.icono} display-5 mb-2 text-dark"></i>
                            <h6 class="mb-0 fw-bold text-dark">${cat.nombre}</h6>
                        </div>
                    </div>
                </div>
            `);
        });
    }

    window.seleccionarCategoria = function(id) {
        categoriaActual = taxonomias.find(c => c.id === id);
        document.getElementById('titulo-categoria-seleccionada').innerHTML = categoriaActual.nombre;
        const lista = document.getElementById('lista-opciones');
        lista.innerHTML = '';
        categoriaActual.opciones.forEach((opc, idx) => {
            lista.insertAdjacentHTML('beforeend', `
                <div class="list-group-item d-flex align-items-center py-2 border-0">
                    <input class="form-check-input service-check" type="checkbox" value="${opc}" id="check-${idx}">
                    <label class="form-check-label ms-2" for="check-${idx}">${opc}</label>
                </div>
            `);
        });
        document.getElementById('panel-categorias').classList.add('d-none');
        document.getElementById('panel-opciones').classList.remove('d-none');
    };

    // Añadir al carro
    document.getElementById('btn-confirmar-seleccion')?.addEventListener('click', () => {
        const checks = document.querySelectorAll('.service-check:checked');
        const precio = parseFloat(document.getElementById('precio-individual').value) || 0;

        if (checks.length === 0) return Swal.fire('Oye', 'Elige algo primero', 'warning');
        
        const nombres = Array.from(checks).map(c => c.value).join(', ');
        carritoTrabajos.push({
            id: Date.now(),
            categoria: categoriaActual.nombre,
            trabajo: nombres,
            anotacion: document.getElementById('anotacion-servicio').value.trim(),
            precio: precio
        });
        
        renderizarCarrito();
        volverACategorias();
    });

    function renderizarCarrito() {
        const lista = document.getElementById('carrito-lista');
        const vacio = document.getElementById('carrito-vacio');
        const tabla = document.getElementById('tabla-carrito');
        
        if (carritoTrabajos.length === 0) {
            vacio.classList.remove('d-none');
            tabla.classList.add('d-none');
        } else {
            vacio.classList.add('d-none');
            tabla.classList.remove('d-none');
            lista.innerHTML = '';
            carritoTrabajos.forEach(i => {
                lista.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td class="ps-3 py-2 small">
                            <strong>${i.trabajo}</strong><br>
                            <span class="text-muted">${i.categoria}${i.anotacion ? ' - '+i.anotacion : ''}</span>
                        </td>
                        <td class="text-end fw-bold">${i.precio.toFixed(2)}€</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-delete-service" onclick="borrarDelCarrito(${i.id})"><i class="ri-delete-bin-line"></i></button>
                        </td>
                    </tr>
                `);
            });
        }
        actualizarTotal();
    }

    function actualizarTotal() {
        const total = carritoTrabajos.reduce((sum, item) => sum + item.precio, 0);
        document.getElementById('txt-total-global').textContent = total.toFixed(2) + ' €';
        document.getElementById('trabajo-materiales').value = total;
        document.getElementById('trabajo-materiales-display').value = total;
        
        let desc = "";
        carritoTrabajos.forEach(i => desc += `[${i.categoria}] ${i.trabajo} ${i.anotacion ? '('+i.anotacion+')' : ''}\n`);
        document.getElementById('trabajo-descripcion').value = desc;
    }

    window.borrarDelCarrito = function(id) {
        carritoTrabajos = carritoTrabajos.filter(i => i.id !== id);
        renderizarCarrito();
    };

    window.volverACategorias = function() {
        document.getElementById('panel-opciones').classList.add('d-none');
        document.getElementById('panel-categorias').classList.remove('d-none');
    };

    // Control de los pasos
    window.validateStep = function(step) {
        if (step === 1) {
            const nombre = document.getElementById('trabajo-nombre').value.trim();
            const apellido = document.getElementById('trabajo-apellido').value.trim();
            const telefono = document.getElementById('trabajo-telefono').value.trim();
            const correo = document.getElementById('trabajo-correo').value.trim();

            if (!nombre || !apellido || !telefono || !correo) {
                Swal.fire('Faltan datos', 'Por favor, rellena todos los campos del cliente.', 'warning');
                return false;
            }
            // Validación básica de teléfono (9 dígitos)
            if (!/^[0-9]{9}$/.test(telefono)) {
                Swal.fire('Teléfono no válido', 'El teléfono debe tener 9 dígitos numéricos.', 'warning');
                return false;
            }
        }
        
        if (step === 2) {
            const marca = document.getElementById('trabajo-marca').value.trim();
            const modelo = document.getElementById('trabajo-modelo').value.trim();

            if (!marca || !modelo) {
                Swal.fire('Datos del coche', 'Debes indicar la marca y el modelo del vehículo.', 'warning');
                return false;
            }
        }

        if (step === 3) {
            if (carritoTrabajos.length === 0) {
                Swal.fire('Sin servicios', 'Añade al menos un servicio para continuar.', 'warning');
                return false;
            }
        }

        return true;
    };

    window.showStep = function(step) {
        document.querySelectorAll('.wizard-step').forEach(el => el.classList.add('d-none'));
        document.getElementById('step-' + step).classList.remove('d-none');
        
        document.querySelectorAll('.step-item').forEach((el, index) => {
            el.classList.remove('active', 'completed');
            if (index + 1 < step) el.classList.add('completed');
            else if (index + 1 === step) el.classList.add('active');
        });

        document.getElementById('btn-prev').classList.toggle('d-none', step === 1);
        if (step === 4) {
            document.getElementById('btn-next').classList.add('d-none');
            document.getElementById('btn-submit').classList.remove('d-none');
        } else {
            document.getElementById('btn-next').classList.remove('d-none');
            document.getElementById('btn-submit').classList.add('d-none');
        }
    };

    document.getElementById('btn-next').addEventListener('click', () => {
        if (currentStep < 4 && validateStep(currentStep)) { 
            currentStep++; 
            showStep(currentStep); 
        }
    });

    document.getElementById('btn-prev').addEventListener('click', () => {
        if (currentStep > 1) { currentStep--; showStep(currentStep); }
    });

    // Buscador de clientes para no repetir fichas
    const buscador = document.getElementById('buscador-cliente');
    buscador.addEventListener('input', function() {
        const q = this.value;
        if (q.length < 3) return document.getElementById('resultados-busqueda-cliente').classList.add('d-none');
        
        fetch(`${apiRoutes.clientes}?q=${q}`).then(res => res.json()).then(data => {
            const resDiv = document.getElementById('resultados-busqueda-cliente');
            resDiv.innerHTML = data.map((c, i) => `
                <div class="search-item" onclick="cargarCliente(${JSON.stringify(c).replace(/"/g, '&quot;')})">
                    <strong>${c.nombre} ${c.apellido}</strong> - ${c.telefono}
                </div>
            `).join('');
            resDiv.classList.remove('d-none');
        });
    });

    window.cargarCliente = function(c) {
        document.getElementById('trabajo-nombre').value = c.nombre;
        document.getElementById('trabajo-apellido').value = c.apellido;
        document.getElementById('trabajo-telefono').value = c.telefono;
        document.getElementById('trabajo-correo').value = c.correo;
        document.getElementById('resultados-busqueda-cliente').classList.add('d-none');
        
        // Si ya tiene coches, que salgan para elegir
        if (c.vehiculos?.length > 0) {
            const list = document.getElementById('lista-vehiculos-cliente');
            list.innerHTML = c.vehiculos.map(v => `
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="elegirCoche('${v.marca}', '${v.modelo}')">
                    ${v.marca} ${v.modelo}
                </button>
            `).join('');
            document.getElementById('vehiculos-cliente-container').classList.remove('d-none');
        }
    };

    window.elegirCoche = function(ma, mo) {
        document.getElementById('trabajo-marca').value = ma;
        document.getElementById('trabajo-modelo').value = mo;
        document.getElementById('btn-next').click();
    };

    // Buscador de marcas
    const buscadorMarca = document.getElementById('trabajo-marca');
    buscadorMarca.addEventListener('input', function() {
        const q = this.value;
        if (q.length < 1) return document.getElementById('resultados-busqueda-marca').classList.add('d-none');
        
        fetch(`${apiRoutes.marcas}?q=${q}`).then(res => res.json()).then(data => {
            const resDiv = document.getElementById('resultados-busqueda-marca');
            resDiv.innerHTML = data.map(m => `
                <div class="search-item" onclick="cargarMarca(${m.id}, '${m.nombre}')">
                    <strong>${m.nombre}</strong>
                </div>
            `).join('');
            resDiv.classList.toggle('d-none', data.length === 0);
        });
    });

    window.cargarMarca = function(id, nombre) {
        document.getElementById('trabajo-marca').value = nombre;
        document.getElementById('trabajo-marca').dataset.id = id;
        document.getElementById('resultados-busqueda-marca').classList.add('d-none');
        document.getElementById('trabajo-modelo').value = '';
        document.getElementById('trabajo-modelo').focus();
    };

    // Buscador de modelos
    const buscadorModelo = document.getElementById('trabajo-modelo');
    buscadorModelo.addEventListener('input', function() {
        const q = this.value;
        const marcaId = document.getElementById('trabajo-marca').dataset.id || '';
        
        fetch(`${apiRoutes.modelos}?q=${q}&marca_id=${marcaId}`).then(res => res.json()).then(data => {
            const resDiv = document.getElementById('resultados-busqueda-modelo');
            resDiv.innerHTML = data.map(m => `
                <div class="search-item" onclick="cargarModelo('${m.nombre}')">
                    <strong>${m.nombre}</strong>
                </div>
            `).join('');
            resDiv.classList.toggle('d-none', data.length === 0);
        });
    });

    window.cargarModelo = function(nombre) {
        document.getElementById('trabajo-modelo').value = nombre;
        document.getElementById('resultados-busqueda-modelo').classList.add('d-none');
    };

    // Cerrar buscadores al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.position-relative')) {
            document.querySelectorAll('.search-results').forEach(el => el.classList.add('d-none'));
        }
    });

    renderCategorias();
});
</script>
@endsection