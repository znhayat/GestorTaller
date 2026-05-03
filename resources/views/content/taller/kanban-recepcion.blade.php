@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
      <h4 class="fw-bold mb-1">Tablero de Recepción</h4>
      <p class="text-muted mb-0">Gestión visual del flujo de entrada y presupuestos</p>
    </div>
    <a href="{{ route('trabajo.create') }}" class="btn btn-primary">
      <i class="ri-add-line me-1"></i> Nuevo Trabajo
    </a>
  </div>

  <!-- Tablero Kanban -->
  <div class="row g-4">
    @foreach($estados as $estadoKey => $config)
    <div class="col-lg-4 col-md-6 col-12">
      <div class="card h-100 shadow-sm">
        <div class="card-header {{ $config['bg'] }} text-white py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white fw-bold">{{ $config['title'] }}</h5>
            <span class="badge bg-white text-dark rounded-pill">{{ $encargos->where('estado', $estadoKey)->count() }}</span>
          </div>
        </div>
        <div class="card-body bg-light p-3" style="min-height: 550px; max-height: 700px; overflow-y: auto;">
          <div class="kanban-column" data-estado="{{ $estadoKey }}">
            @forelse($encargos->where('estado', $estadoKey) as $encargo)
            <div class="card mb-3 border-start border-{{ $config['color'] }} border-3 shadow-sm kanban-item" 
                 data-id="{{ $encargo->id }}" data-estado-actual="{{ $estadoKey }}" style="cursor: grab;">
              <div class="card-body p-3">
                <!-- Info Cliente/Coche -->
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div>
                    <h6 class="mb-0 fw-bold">{{ $encargo->vehiculo->marca }} {{ $encargo->vehiculo->modelo }}</h6>
                    <small class="text-muted">{{ $encargo->vehiculo->cliente->nombre }} {{ $encargo->vehiculo->cliente->apellido }}</small>
                  </div>
                  <span class="badge bg-{{ $config['color'] }}">{{ $config['title'] }}</span>
                </div>

                <div class="mb-2 small">
                  <span class="text-muted d-block"><i class="ri-phone-line"></i> {{ $encargo->vehiculo->cliente->telefono }}</span>
                  @if($encargo->cita_revision)
                  <span class="text-primary d-block mt-1 fw-bold"><i class="ri-calendar-line"></i> Cita: {{ date('d/m/Y', strtotime($encargo->cita_revision)) }} - {{ \Carbon\Carbon::parse($encargo->hora_cita)->format('H:i') }}h</span>
                  @endif
                </div>

                <div class="bg-white rounded p-2 mb-3 border small text-secondary">
                  {{ Str::limit($encargo->descripcion, 85) }}
                </div>

                <!-- Info Presupuesto -->
                @if($encargo->presupuesto)
                <div class="bg-white p-2 rounded mb-3 border border-info border-opacity-50 shadow-xs">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="text-muted">Estimación:</small>
                    <span class="fw-bold text-dark">{{ number_format($encargo->presupuesto->estimacion_inicial ?? $encargo->presupuesto->total, 2) }} €</span>
                  </div>
                  @if($encargo->presupuesto->total != ($encargo->presupuesto->estimacion_inicial ?? $encargo->presupuesto->total))
                  <div class="d-flex justify-content-between align-items-center border-top pt-1">
                    <small class="text-primary fw-bold">Revisado:</small>
                    <span class="fw-bold text-primary">{{ number_format($encargo->presupuesto->total, 2) }} €</span>
                  </div>
                  @endif
                </div>
                @endif

                <!-- Acciones Rápidas -->
                <div class="d-flex gap-2 flex-wrap">
                  @if($estadoKey == 'En Revision')
                    @if($encargo->presupuesto)
                    <button type="button" class="btn btn-warning btn-sm flex-grow-1" onclick="abrirModalUpdatePresupuesto({{ $encargo->presupuesto->id }}, {{ $encargo->presupuesto->total }})">
                      <i class="ri-money-euro-circle-line me-1"></i> Ajustar PPT
                    </button>
                    @else
                    <a href="{{ route('presupuestos.create', ['encargo_id' => $encargo->id]) }}" class="btn btn-warning btn-sm flex-grow-1">
                      <i class="ri-file-add-line me-1"></i> Crear PPT
                    </a>
                    @endif
                  @endif

                  @if($estadoKey == 'Presupuesto Enviado')
                  <button type="button" class="btn btn-success btn-sm flex-grow-1" onclick="mostrarModalFechaTrabajo({{ $encargo->id }})">
                    <i class="ri-check-line me-1"></i> Aceptar
                  </button>
                  <button type="button" class="btn btn-outline-secondary btn-sm" onclick="moverEstado({{ $encargo->id }}, 'Cancelado')" title="Rechazar presupuesto">
                    <i class="ri-close-line me-1"></i> Rechazar
                  </button>
                  @endif

                  <a href="{{ route('encargos.edit', $encargo->id) }}?origin=recepcion" class="btn btn-outline-primary btn-sm flex-grow-1">
                    <i class="ri-edit-line"></i> Ficha
                  </a>
                  <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarEncargo({{ $encargo->id }})">
                    <i class="ri-delete-bin-line me-1"></i> Eliminar
                  </button>
                </div>
              </div>
            </div>
            @empty
            <div class="text-center text-muted py-5 opacity-50">
              <i class="ri-inbox-line fs-1"></i>
              <p class="mt-2 mb-0">Sin tareas aquí</p>
            </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>

<!-- Modal: Programar Cita (Aceptar Presupuesto) -->
<div class="modal fade" id="modalFechaTrabajo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-primary py-3">
          <h5 class="modal-title text-white fw-bold"><i class="ri-calendar-check-line me-2"></i>Agendar Ingreso al Taller</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          <div class="row g-0">
            <div class="col-md-7 p-4 border-end">
              <input type="hidden" id="encargo_id_work">
              <div class="mb-4">
                <label class="form-label fw-bold text-dark">Día y Hora de Recepción</label>
                <div class="input-group">
                  <span class="input-group-text bg-primary text-white border-primary"><i class="ri-time-line"></i></span>
                  <input type="text" id="fecha_inicio_trabajo" class="form-control form-control-lg border-primary shadow-sm" placeholder="Selecciona el momento...">
                </div>
              </div>
              <div class="mb-0">
                <label class="form-label fw-bold text-dark">Estimación de Salida</label>
                <div class="input-group">
                  <span class="input-group-text bg-info text-white border-info"><i class="ri-car-line"></i></span>
                  <input type="text" id="fecha_recogida_estimada" class="form-control form-control-lg border-info shadow-sm" placeholder="¿Cuándo estará terminado?">
                </div>
              </div>
            </div>
            <div class="col-md-5 bg-light p-4">
              <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="ri-history-line me-1"></i>Agenda del día:</h6>
              <div id="listaOcupadas" class="list-group list-group-flush shadow-sm rounded bg-white overflow-auto" style="max-height: 250px;">
                  <div class="text-center py-5 text-muted">Pincha un día libre</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light border-top p-3">
          <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Posponer</button>
          <button type="button" class="btn btn-primary px-5 fw-bold" onclick="aceptarYProgramar()">INICIAR TRABAJO</button>
        </div>
      </div>
    </div>
</div>

<!-- Modal: Actualizar Importe PPT -->
<div class="modal fade" id="modalUpdatePPT" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-primary py-3">
          <h5 class="modal-title text-white fw-bold"><i class="ri-money-euro-circle-line me-2"></i>Ajustar Presupuesto</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4 text-center">
            <input type="hidden" id="update_ppt_id">
            <label class="form-label fw-bold text-dark fs-5 mb-3">Nuevo Importe Total (€)</label>
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-primary border-primary text-white">€</span>
                <input type="number" id="update_ppt_total" class="form-control border-primary text-center fw-bold" step="0.01">
            </div>
        </div>
        <div class="modal-footer bg-light border-top p-3 text-center">
          <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">CANCELAR</button>
          <button type="button" class="btn btn-primary px-5 fw-bold" onclick="ejecutarUpdatePPT()">GUARDAR</button>
        </div>
      </div>
    </div>
</div>
@endsection

@section('vendor-script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script>
  let fpInicio = null, fpFin = null;

  document.addEventListener('DOMContentLoaded', function() {
    // Inicialización del Tablero Kanban (Drag & Drop)
    document.querySelectorAll('.kanban-column').forEach(column => {
      new Sortable(column, {
        group: 'kanban',
        animation: 300,
        ghostClass: 'opacity-50',
        onEnd: function(evt) {
          const item = evt.item, 
                newEstado = evt.to.getAttribute('data-estado'),
                oldEstado = item.getAttribute('data-estado-actual'),
                id = item.getAttribute('data-id');

          if (oldEstado === newEstado) return;

          // Reglas de negocio para movimientos lógicos
          const flujoValido = {
            'Cita Agendada': ['En Revision'],
            'En Revision': ['Presupuesto Enviado'],
            'Presupuesto Enviado': ['Pendiente Inicio', 'Cancelado']
          };

          if (!flujoValido[oldEstado] || !flujoValido[oldEstado].includes(newEstado)) {
             window.showToast('Movimiento no permitido: sigue el orden lógico.', 'warning');
             evt.from.appendChild(item);
             return;
          }

          // Si aceptamos presupuesto, forzamos agendar fecha
          if (newEstado === 'Pendiente Inicio' && oldEstado === 'Presupuesto Enviado') {
             evt.from.appendChild(item);
             mostrarModalFechaTrabajo(id);
             return;
          }

          moverEstado(id, newEstado);
        }
      });
    });
  });

  // Muestra el modal de agendar y carga disponibilidad
  function mostrarModalFechaTrabajo(id) {
    document.getElementById('encargo_id_work').value = id;
    fetch('/api/disponibilidad-mensual')
        .then(res => res.json())
        .then(dates => {
            window.occupiedDates = dates;
            inicializarCalendarios();
        });
  }

  // Configura los calendarios Flatpickr y resalta días ocupados
  function inicializarCalendarios() {
    if(!fpInicio) {
        fpInicio = flatpickr("#fecha_inicio_trabajo", {
            enableTime: true, dateFormat: "Y-m-d H:i", minDate: "today", locale: "es", time_24hr: true,
            onDayCreate: (dObj, dStr, fp, dayElem) => {
                const dateStr = dayElem.dateObj.toISOString().split('T')[0];
                if (window.occupiedDates && window.occupiedDates.includes(dateStr)) {
                    dayElem.classList.add("has-appointments");
                }
            },
            disable: [date => (date.getDay() === 0 || date.getDay() === 6)],
            onChange: (selectedDates, dateStr, instance) => {
                if(selectedDates.length > 0) {
                    verificarDisponibilidad(dateStr.split(" ")[0]);
                }
            }
        });
    } else { fpInicio.clear(); }

    if(!fpFin) {
        fpFin = flatpickr("#fecha_recogida_estimada", {
            dateFormat: "Y-m-d", minDate: "today", locale: "es",
            disable: [date => (date.getDay() === 0 || date.getDay() === 6)]
        });
    } else { fpFin.clear(); }

    new bootstrap.Modal(document.getElementById('modalFechaTrabajo')).show();
  }

  // Consulta la agenda del día seleccionado (API)
  function verificarDisponibilidad(fecha) {
      const list = document.getElementById('listaOcupadas');
      list.innerHTML = '<div class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div></div>';

      fetch('/api/disponibilidad?date=' + fecha)
        .then(res => res.json())
        .then(ocupadas => {
            list.innerHTML = ocupadas.length ? '' : '<div class="text-center py-5 text-success fw-bold">Día completamente libre</div>';
            ocupadas.forEach(cita => {
                list.innerHTML += `
                    <div class="list-group-item d-flex align-items-center border-0 px-0 py-2">
                        <span class="badge ${cita.tipo === 'recepcion' ? 'bg-primary' : 'bg-info'} text-white me-3" style="width: 55px;">${cita.hora}</span>
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-dark small">${cita.cliente}</span>
                            <small class="text-muted" style="font-size: 0.7rem;">${cita.tipo.toUpperCase()}</small>
                        </div>
                    </div>`;
            });
        });
  }

  // Acción final de aceptar presupuesto y guardar fechas
  function aceptarYProgramar() {
    const id = document.getElementById('encargo_id_work').value,
          inicio = document.getElementById('fecha_inicio_trabajo').value,
          fin = document.getElementById('fecha_recogida_estimada').value;

    if (!inicio || !fin) return window.showToast('Completa las fechas en el calendario', 'warning');

    const partes = inicio.split(" ");
    fetch('/encargos/' + id + '/aceptar-programar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ fecha_inicio: partes[0], hora_inicio: partes[1] || '09:00', fecha_recogida: fin })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
            window.showToast(data.message, 'success');
            setTimeout(() => location.reload(), 800);
        }
      });
  }

  // Cambio de estado directo (silencioso)
  function moverEstado(id, nuevoEstado) {
    fetch(`/encargos/${id}/status`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ estado: nuevoEstado })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
            window.showToast(data.message, 'success');
            setTimeout(() => location.reload(), 800);
        }
      });
  }

  // Modales de actualización de precio (PPT)
  function abrirModalUpdatePresupuesto(id, actual) {
    document.getElementById('update_ppt_id').value = id;
    document.getElementById('update_ppt_total').value = actual;
    new bootstrap.Modal(document.getElementById('modalUpdatePPT')).show();
  }

  function ejecutarUpdatePPT() {
    const id = document.getElementById('update_ppt_id').value,
          total = document.getElementById('update_ppt_total').value;
    if (!total || total <= 0) return window.showToast('Importe no válido', 'warning');

    fetch(`/presupuestos/${id}/quick-update`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ total: total })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            window.showToast(data.message, 'success');
            setTimeout(() => location.reload(), 800);
        }
    });
  }

  // Eliminación con confirmación de seguridad
  function eliminarEncargo(id) {
    Swal.fire({
      title: '¿Eliminar registro?', text: 'Esta acción no se puede deshacer.', icon: 'warning',
      showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Sí, borrar'
    }).then(res => {
      if (res.isConfirmed) {
        fetch('/encargos/' + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }})
          .then(res => res.json())
          .then(data => {
            if (data.success) {
                window.showToast(data.message, 'success');
                setTimeout(() => location.reload(), 800);
            }
          });
      }
    });
  }
</script>

<style>
    .has-appointments { border: 1.5px solid #d32f2f !important; background: #fff5f5 !important; font-weight: bold; position: relative; }
    .has-appointments::after { content: ""; position: absolute; bottom: 3px; left: 50%; transform: translateX(-50%); width: 4px; height: 4px; border-radius: 50%; background: #d32f2f; }
</style>
@endsection