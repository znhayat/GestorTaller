@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
      <h4 class="fw-bold mb-1">Tablero de Recepción</h4>
      <p class="text-muted mb-0">Listado de entrada y presupuestos</p>
    </div>
    <a href="{{ route('trabajo.create') }}" class="btn btn-primary">
      <i class="ri-add-line me-1"></i> Nuevo Trabajo
    </a>
  </div>

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

                <div class="d-flex gap-2 flex-wrap">
                  @if($estadoKey == 'En Revision')
                    @if($encargo->presupuesto)
                    <button type="button" class="btn btn-warning btn-sm flex-grow-1" onclick="abrirModalUpdatePresupuesto({{ $encargo->presupuesto->id }}, {{ $encargo->presupuesto->total }})">
                      Ajustar PPT
                    </button>
                    @else
                    <a href="{{ route('presupuestos.create', ['encargo_id' => $encargo->id]) }}" class="btn btn-warning btn-sm flex-grow-1">
                      Crear PPT
                    </a>
                    @endif
                  @endif

                  @if($estadoKey == 'Presupuesto Enviado')
                  <button type="button" class="btn btn-success btn-sm flex-grow-1" onclick="mostrarModalFechaTrabajo({{ $encargo->id }})">
                    Aceptar
                  </button>
                  <button type="button" class="btn btn-outline-secondary btn-sm" onclick="moverEstado({{ $encargo->id }}, 'Cancelado')">
                    Rechazar
                  </button>
                  @endif

                  <a href="{{ route('encargos.edit', $encargo->id) }}?origin=recepcion" class="btn btn-outline-primary btn-sm flex-grow-1">
                    Ficha
                  </a>
                  <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarEncargo({{ $encargo->id }})">
                    <i class="ri-delete-bin-line me-1"></i> Eliminar
                  </button>
                </div>
              </div>
            </div>
            @empty
            <div class="text-center text-muted py-5 opacity-50">
              <p class="mt-2 mb-0">Vacío</p>
            </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>

<!-- Modal para agendar cuando aceptan el presupuesto -->
<div class="modal fade" id="modalFechaTrabajo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-primary py-3">
          <h5 class="modal-title text-white fw-bold">Agendar entrada al taller</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          <div class="row g-0">
            <div class="col-md-7 p-4 border-end">
              <input type="hidden" id="encargo_id_work">
              <div class="mb-4">
                <label class="form-label fw-bold">Día y Hora de entrada</label>
                <input type="text" id="fecha_inicio_trabajo" class="form-control">
              </div>
              <div class="mb-0">
                <label class="form-label fw-bold">Fecha prevista de entrega</label>
                <input type="text" id="fecha_recogida_estimada" class="form-control">
              </div>
            </div>
            <div class="col-md-5 bg-light p-4">
              <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Agenda del día:</h6>
              <div id="listaOcupadas" class="list-group list-group-flush bg-white overflow-auto" style="max-height: 250px;"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light border-top p-3">
          <button type="button" class="btn btn-primary px-5 fw-bold" onclick="aceptarYProgramar()">CONFIRMAR E INICIAR</button>
        </div>
      </div>
    </div>
</div>

<!-- Modal rápido para cambiar el precio del presupuesto -->
<div class="modal fade" id="modalUpdatePPT" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-primary py-3">
          <h5 class="modal-title text-white fw-bold">Ajustar Importe</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4 text-center">
            <input type="hidden" id="update_ppt_id">
            <label class="form-label fw-bold mb-3">Nuevo Total (€)</label>
            <input type="number" id="update_ppt_total" class="form-control form-control-lg text-center fw-bold" step="0.01">
        </div>
        <div class="modal-footer bg-light border-top p-3 text-center">
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
    const ordenEstados = ['Cita Agendada', 'En Revision', 'Presupuesto Enviado', 'Pendiente Inicio', 'Cancelado'];

    document.querySelectorAll('.kanban-column').forEach(column => {
      new Sortable(column, {
        group: 'kanban',
        animation: 300,
        onEnd: function(evt) {
          const item = evt.item, 
                newEstado = evt.to.getAttribute('data-estado'),
                oldEstado = item.getAttribute('data-estado-actual'),
                id = item.getAttribute('data-id');

          if (oldEstado === newEstado) return;

          const oldIdx = ordenEstados.indexOf(oldEstado);
          const newIdx = ordenEstados.indexOf(newEstado);

          // Si mueven la tarjeta atrás, preguntamos para confirmar
          if (newIdx < oldIdx && newEstado !== 'Cancelado') {
            Swal.fire({
              title: '¿Mover atrás?',
              text: '¿Quieres devolver este trabajo a una fase anterior?',
              icon: 'question',
              showCancelButton: true,
              confirmButtonText: 'Sí, mover',
              cancelButtonText: 'No'
            }).then((result) => {
              if (result.isConfirmed) {
                moverEstado(id, newEstado);
              } else {
                evt.from.appendChild(item);
              }
            });
            return;
          }

          // Control de flujo (para no saltarse pasos)
          const flujoValido = {
            'Cita Agendada': ['En Revision', 'Cancelado'],
            'En Revision': ['Presupuesto Enviado', 'Cancelado'],
            'Presupuesto Enviado': ['Pendiente Inicio', 'Cancelado']
          };

          if (flujoValido[oldEstado] && !flujoValido[oldEstado].includes(newEstado)) {
             window.showToast('Sigue el orden del tablero', 'warning');
             evt.from.appendChild(item);
             return;
          }

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

  // Modal de fechas
  function mostrarModalFechaTrabajo(id) {
    document.getElementById('encargo_id_work').value = id;
    fetch('/api/disponibilidad-mensual')
        .then(res => res.json())
        .then(dates => {
            window.occupiedDates = dates;
            inicializarCalendarios();
        });
  }

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
            onChange: (selectedDates, dateStr) => {
                if(selectedDates.length > 0) {
                    verificarDisponibilidad(dateStr.split(" ")[0]);
                }
            }
        });
    }
    if(!fpFin) {
        fpFin = flatpickr("#fecha_recogida_estimada", {
            dateFormat: "Y-m-d", minDate: "today", locale: "es",
            disable: [date => (date.getDay() === 0 || date.getDay() === 6)]
        });
    }
    new bootstrap.Modal(document.getElementById('modalFechaTrabajo')).show();
  }

  function verificarDisponibilidad(fecha) {
      const list = document.getElementById('listaOcupadas');
      fetch('/api/disponibilidad?date=' + fecha)
        .then(res => res.json())
        .then(ocupadas => {
            list.innerHTML = ocupadas.length ? '' : '<div class="text-center py-4">Día libre</div>';
            ocupadas.forEach(cita => {
                list.innerHTML += `<div class="list-group-item small">${cita.hora} - ${cita.cliente}</div>`;
            });
        });
  }

  function aceptarYProgramar() {
    const id = document.getElementById('encargo_id_work').value,
          inicio = document.getElementById('fecha_inicio_trabajo').value,
          fin = document.getElementById('fecha_recogida_estimada').value;

    if (!inicio || !fin) return window.showToast('Elige las fechas', 'warning');

    const partes = inicio.split(" ");
    fetch('/encargos/' + id + '/aceptar-programar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ fecha_inicio: partes[0], hora_inicio: partes[1], fecha_recogida: fin })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) { location.reload(); }
      });
  }

  function moverEstado(id, nuevoEstado) {
    fetch(`/encargos/${id}/status`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ estado: nuevoEstado })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) { location.reload(); }
      });
  }

  function abrirModalUpdatePresupuesto(id, actual) {
    document.getElementById('update_ppt_id').value = id;
    document.getElementById('update_ppt_total').value = actual;
    new bootstrap.Modal(document.getElementById('modalUpdatePPT')).show();
  }

  function ejecutarUpdatePPT() {
    const id = document.getElementById('update_ppt_id').value,
          total = document.getElementById('update_ppt_total').value;
    fetch(`/presupuestos/${id}/quick-update`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ total: total })
    }).then(() => location.reload());
  }

  function eliminarEncargo(id) {
    if(confirm('¿Seguro que quieres borrar este trabajo?')) {
        fetch('/encargos/' + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }})
        .then(() => location.reload());
    }
  }
</script>
@endsection