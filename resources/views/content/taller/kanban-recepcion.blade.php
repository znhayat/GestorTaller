@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
      <h4 class="fw-bold mb-1">Tablero de Recepcion</h4>
      <p class="text-muted mb-0">Arrastre las tarjetas para avanzar el trabajo</p>
    </div>
    <a href="{{ route('trabajo.create') }}" class="btn btn-primary">
      <i class="ri-add-line me-1"></i> Nuevo Trabajo
    </a>
  </div>

  <div class="row g-4">
    @foreach($estados as $estadoKey => $config)
    <div class="col-lg-4 col-md-6 col-12">
      <div class="card h-100">
        <div class="card-header {{ $config['bg'] }} text-white">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-0 text-white">{{ $config['title'] }}</h5>
              <small class="text-white-50">{{ $config['description'] }}</small>
            </div>
            <span class="badge bg-white text-dark rounded-pill">{{ $encargos->where('estado', $estadoKey)->count() }}</span>
          </div>
        </div>
        <div class="card-body bg-light p-3" style="min-height: 550px; max-height: 600px; overflow-y: auto;">
          <div class="kanban-column" data-estado="{{ $estadoKey }}">
            @forelse($encargos->where('estado', $estadoKey) as $encargo)
            <div class="card mb-3 shadow-sm border-start border-{{ $config['color'] }} border-3 kanban-item" data-id="{{ $encargo->id }}" data-estado-actual="{{ $estadoKey }}" style="cursor: grab;">
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div>
                    <h6 class="mb-0 fw-bold">{{ $encargo->vehiculo->marca }} {{ $encargo->vehiculo->modelo }}</h6>
                    <small class="text-muted">{{ $encargo->vehiculo->cliente->nombre }} {{ $encargo->vehiculo->cliente->apellido }}</small>
                  </div>
                  <span class="badge bg-{{ $config['color'] }}">{{ $config['title'] }}</span>
                </div>

                <div class="mb-2">
                  <small class="text-muted d-block"><i class="ri-phone-line me-1"></i> {{ $encargo->vehiculo->cliente->telefono }}</small>
                  <small class="text-muted d-block"><i class="ri-mail-line me-1"></i> {{ $encargo->vehiculo->cliente->correo }}</small>
                  @if($encargo->cita_revision)
                  <small class="text-primary d-block mt-2"><i class="ri-calendar-line me-1"></i> <strong>Revision:</strong> {{ date('d/m/Y', strtotime($encargo->cita_revision)) }} - {{ \Carbon\Carbon::parse($encargo->hora_cita)->format('H:i') }}h</small>
                  @endif
                </div>

                <div class="bg-white rounded p-2 mb-3 border">
                  <small class="text-secondary">{{ Str::limit($encargo->descripcion, 80) }}</small>
                </div>

                @if($encargo->presupuesto)
                <div class="bg-label-info p-2 rounded mb-3 small border border-info border-opacity-25">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-bold text-dark">{{ number_format($encargo->presupuesto->estimacion_inicial ?? $encargo->presupuesto->total, 2) }} €</span>
                  </div>
                  @if($encargo->presupuesto->total != ($encargo->presupuesto->estimacion_inicial ?? $encargo->presupuesto->total))
                  <div class="d-flex justify-content-between align-items-center border-top pt-1">
                    <span class="text-primary"><i class="ri-checkbox-circle-fill me-1"></i> Pres. Revisado:</span>
                    <span class="fw-bold text-primary">{{ number_format($encargo->presupuesto->total, 2) }} €</span>
                  </div>
                  @endif
                </div>
                @endif

                <div class="d-flex gap-2 flex-wrap">
                  @if($estadoKey == 'En Revision')
                  @if($encargo->presupuesto)
                  <button type="button" class="btn btn-warning btn-sm flex-grow-1" onclick="abrirModalUpdatePresupuesto({{ $encargo->presupuesto->id }}, {{ $encargo->presupuesto->total }})" title="Actualizar Presupuesto tras Revisión">
                    <i class="ri-refresh-line me-1"></i> Actualizar PPT
                  </button>
                  @else
                  <a href="{{ route('presupuestos.create', ['encargo_id' => $encargo->id]) }}" class="btn btn-warning btn-sm flex-grow-1" title="Crear Nuevo Presupuesto">
                    <i class="ri-file-copy-line me-1"></i> Crear PPT
                  </a>
                  @endif
                  @endif

                  @if($estadoKey == 'Presupuesto Enviado')
                  <button type="button" class="btn btn-success btn-sm flex-grow-1" onclick="mostrarModalFechaTrabajo({{ $encargo->id }})" title="Aceptar Presupuesto y Agendar">
                    <i class="ri-check-line me-1"></i> Aceptar PPT
                  </button>
                  <button type="button" class="btn btn-secondary btn-sm" onclick="moverEstado({{ $encargo->id }}, 'Cancelado')" title="Rechazar y Mover al Historial">
                    <i class="ri-close-line me-1"></i> Rechazar
                  </button>
                  @endif

                  <a href="{{ route('encargos.edit', $encargo->id) }}?origin=recepcion" class="btn btn-primary btn-sm flex-grow-1" aria-label="Editar encargo de {{ $encargo->vehiculo->marca }}" title="Editar Ficha Técnica">
                    <i class="ri-edit-line me-1" aria-hidden="true"></i> Ficha Técnico
                  </a>
                  <button type="button" class="btn btn-danger btn-sm" onclick="eliminarEncargo({{ $encargo->id }})" aria-label="Eliminar exp." title="Eliminar Registro Completamente">
                    <i class="ri-delete-bin-line me-1" aria-hidden="true"></i> Borrar
                  </button>
                </div>

                <div class="text-center mt-2">
                  <small class="text-muted"><i class="ri-arrow-left-right-line me-1"></i> Arrastre para avanzar</small>
                </div>
              </div>
            </div>
            @empty
            <div class="text-center text-muted py-5">
              <i class="ri-inbox-line fs-1"></i>
              <p class="mt-2 mb-0">No hay trabajos</p>
            </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>

<!-- Modal para programar fecha de inicio y fin al aceptar presupuesto -->
<div class="modal fade" id="modalFechaTrabajo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-primary py-3">
          <h5 class="modal-title text-white fw-bold"><i class="ri-calendar-check-line me-2"></i>Programar Ingreso al Taller</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          <div class="row g-0">
            <!-- Columna del Calendario -->
            <div class="col-md-7 p-4 border-end">
              <input type="hidden" id="encargo_id_work">
              
              <div class="mb-4">
                <label class="form-label fw-bold text-dark fs-6">Fecha y Hora de Inicio (Recepción)</label>
                <div class="input-group">
                  <span class="input-group-text bg-primary border-primary text-white"><i class="ri-time-line"></i></span>
                  <input type="text" id="fecha_inicio_trabajo" class="form-control form-control-lg border-primary shadow-sm" placeholder="Selecciona día y hora...">
                </div>
              </div>

              <div class="mb-0">
                <label class="form-label fw-bold text-dark fs-6">Fecha Estimada de Entrega</label>
                <div class="input-group">
                  <span class="input-group-text bg-info border-info text-white"><i class="ri-car-line"></i></span>
                  <input type="text" id="fecha_recogida_estimada" class="form-control form-control-lg border-info shadow-sm" placeholder="¿Cuándo estará listo?">
                </div>
              </div>
            </div>

            <!-- Columna de Agenda del Día -->
            <div class="col-md-5 bg-light p-4">
              <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="ri-history-line me-1"></i>Ocupación para el día seleccionado:</h6>
              <div id="disponibilidadContenedor">
                  <div id="listaOcupadas" class="list-group list-group-flush shadow-sm rounded bg-white">
                      <div class="text-center py-5 text-muted bg-white">
                        <i class="ri-calendar-2-line fs-1 d-block mb-2"></i>
                        Pincha un día para ver<br>la disponibilidad
                      </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light border-top p-3">
          <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Posponer</button>
          <button type="button" class="btn btn-primary px-5 fw-bold" onclick="aceptarYProgramar()">
              <i class="ri-check-double-line me-1"></i>INICIAR TRABAJO
          </button>
        </div>
      </div>
    </div>
</div>

<!-- Modal para actualizar importe de presupuesto (PPT) -->
<div class="modal fade" id="modalUpdatePPT" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-primary py-3">
          <h5 class="modal-title text-white fw-bold"><i class="ri-money-euro-circle-line me-2"></i>Actualizar Importe PPT</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
            <input type="hidden" id="update_ppt_id">
            <div class="mb-3">
                <label class="form-label fw-bold text-dark fs-6">Nuevo Importe Total (€)</label>
                <div class="input-group">
                    <span class="input-group-text bg-primary border-primary text-white"><i class="ri-money-euro-circle-line"></i></span>
                    <input type="number" id="update_ppt_total" class="form-control form-control-lg border-primary shadow-sm" step="0.01" placeholder="0.00">
                </div>
                <small class="text-muted mt-2 d-block">Introduce el precio revisado tras la inspección física del vehículo.</small>
            </div>
        </div>
        <div class="modal-footer bg-light border-top p-3 text-center">
          <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">CANCELAR</button>
          <button type="button" class="btn btn-primary px-5 fw-bold" onclick="ejecutarUpdatePPT()">
              <i class="ri-save-3-line me-1"></i>GUARDAR CAMBIOS
          </button>
        </div>
      </div>
    </div>
</div>
@endsection

@section('vendor-script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Flatpickr CSS y JS por CDN para que la experiencia de calendario UI sea TOP -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

<script>
  let fpInicio = null;
  let fpFin = null;

  document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando...');

    // Obtener todas las columnas kanban
    var columns = document.querySelectorAll('.kanban-column');
    console.log('Columnas encontradas:', columns.length);

    // Configurar Sortable para cada columna
    columns.forEach(function(column, index) {
      console.log('Inicializando columna', index, 'con estado:', column.getAttribute('data-estado'));

      new Sortable(column, {
        group: {
          name: 'kanban',
          pull: true,
          revertClone: false
        },
        animation: 300,
        ghostClass: 'opacity-50',
        dragClass: 'cursor-grabbing',
        onEnd: function(evt) {
          console.log('Drag ended');
          var item = evt.item;
          var newEstado = evt.to.getAttribute('data-estado');
          var encargoId = item.getAttribute('data-id');
          var oldEstado = item.getAttribute('data-estado-actual');

          console.log('Movimiento:', oldEstado, '->', newEstado, 'ID:', encargoId);

          // Diccionario formal de transiciones válidas dictado por lógica de negocio
          const transiciones = {
            'Cita Agendada': ['En Revision'],
            'En Revision': ['Presupuesto Enviado'],
            'Presupuesto Enviado': ['Pendiente Inicio', 'Cancelado']
          };

          if (oldEstado === newEstado) return;

          // Verificamos estricamente en el cliente si es un salto inválido o retroceso
          if (!transiciones[oldEstado] || !transiciones[oldEstado].includes(newEstado)) {
             window.showToast('Movimiento Denegado: El proceso requiere seguir un orden secuencial lógico o la tarjeta se encuentra en estado terminal.', 'warning');
             evt.from.appendChild(item); // Retorna inmediatamente la tarjeta visualmente a su origen
             return;
          }

          // Si pasamos a "Pendiente Inicio", mostramos el calendario/modal en vez de mover directo
          if (newEstado === 'Pendiente Inicio' && oldEstado === 'Presupuesto Enviado') {
             evt.from.appendChild(item); // Retorna la tarjeta temporalmente a origen hasta que acabe el modal
             mostrarModalFechaTrabajo(encargoId);
             return;
          }

          moverEstado(encargoId, newEstado, item, evt.from);
        }
      });
    });
  });

  function mostrarModalFechaTrabajo(encargoId) {
    document.getElementById('encargo_id_work').value = encargoId;

    // Cargamos todas las fechas ocupadas antes de mostrar el calendario
    fetch('/api/disponibilidad-mensual') // Necesitamos crear este endpoint o usar uno existente
        .then(res => res.json())
        .then(dates => {
            window.occupiedDates = dates;
            inicializarCalendarios();
            var myModal = new bootstrap.Modal(document.getElementById('modalFechaTrabajo'));
            myModal.show();
        });
  }

  function inicializarCalendarios() {
    // Inicializar Flatpickr si no lo hemos hecho o refrescarlo
    if(!fpInicio) {
        fpInicio = flatpickr("#fecha_inicio_trabajo", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            locale: "es",
            time_24hr: true,
            defaultHour: 8,
            minTime: "08:00",
            maxTime: "20:00",
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                // Obtenemos la fecha del día en formato YYYY-MM-DD
                const date = dayElem.dateObj;
                const dateStr = date.getFullYear() + "-" + 
                               String(date.getMonth() + 1).padStart(2, '0') + "-" + 
                               String(date.getDate()).padStart(2, '0');
                
                // Si el día tiene citas (lo sabremos por una variable global), añadimos la clase
                if (window.occupiedDates && window.occupiedDates.includes(dateStr)) {
                    dayElem.classList.add("has-appointments");
                    dayElem.title = "Día con citas programadas";
                }
            },
            disable: [
                function(date) {
                    // Deshabilitar Sábado (6) y Domingo (0)
                    return (date.getDay() === 0 || date.getDay() === 6);
                }
            ],
            onChange: function(selectedDates, dateStr, instance) {
                if(selectedDates.length > 0) {
                    let d = selectedDates[0];
                    let fechaSolo = dateStr.split(" ")[0];
                    if(fpFin) fpFin.set("minDate", fechaSolo);
                    
                    // Control de horario partido (15:00 a 17:00 cerrado)
                    let hour = d.getHours();
                    if(hour >= 15 && hour < 17) {
                        d.setHours(17);
                        d.setMinutes(0);
                        instance.setDate(d, false);
                        Swal.fire({
                            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                            icon: 'info', title: 'Taller cerrado de 15:00 a 17:00. Hora ajustada.'
                        });
                    }

                    verificarDisponibilidad(fechaSolo);
                }
            }
        });
    } else {
        fpInicio.clear();
    }

    if(!fpFin) {
        fpFin = flatpickr("#fecha_recogida_estimada", {
            dateFormat: "Y-m-d",
            minDate: "today",
            locale: "es",
            disable: [
                function(date) {
                    return (date.getDay() === 0 || date.getDay() === 6);
                }
            ]
        });
    } else {
        fpFin.clear();
    }

    var modal = new bootstrap.Modal(document.getElementById('modalFechaTrabajo'));
    modal.show();
  }

  function aceptarYProgramar() {
    var encargoId = document.getElementById('encargo_id_work').value;
    var inicioVal = document.getElementById('fecha_inicio_trabajo').value;
    var fechaRecogida = document.getElementById('fecha_recogida_estimada').value;

    if (!inicioVal || !fechaRecogida) {
      Swal.fire('Error', 'Debe establecer ambos valores en el calendario.', 'error');
      return;
    }

    // Split de flatpickr "Y-m-d H:i"
    var partes = inicioVal.split(" ");
    var fechaInicio = partes[0];
    var horaInicio = partes[1] ? partes[1] : '09:00';

    // Ejecutamos la acción directamente sin bloquear la pantalla

    fetch('/encargos/' + encargoId + '/aceptar-programar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          fecha_inicio: fechaInicio,
          hora_inicio: horaInicio,
          fecha_recogida: fechaRecogida
        })
      })
      .then(function(response) {
        return response.json();
      })
      .then(function(data) {
        if (data.success) {
          window.showToast(data.message, 'success');
          setTimeout(function() {
            location.reload();
          }, 800);
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message
          });
        }
      })
      .catch(function(error) {
        console.error('Error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al actualizar'
        });
      });
  }

  // Consulta AJAX al hacer clic en el calendario de Flatpickr
  function verificarDisponibilidad(fechaIso) {
      let ulList = document.getElementById('listaOcupadas');
      ulList.innerHTML = '<div class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary" role="status"></div></div>';

      fetch('/api/disponibilidad?date=' + fechaIso)
        .then(response => response.json())
        .then(ocupadas => {
            ulList.innerHTML = '';
            if(ocupadas && ocupadas.length > 0) {
                ocupadas.forEach(cita => {
                    let badgeClass = cita.tipo === 'recepcion' ? 'bg-primary' : 'bg-info';
                    let icon = cita.tipo === 'recepcion' ? 'ri-login-circle-line' : 'ri-tools-line';
                    
                    let item = document.createElement('div');
                    item.className = 'list-group-item d-flex align-items-center border-0 px-0 py-2';
                    item.innerHTML = `
                        <span class="badge ${badgeClass} text-white me-3" style="width: 55px; opacity: 0.9;">${cita.hora}</span>
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-dark small">${cita.cliente}</span>
                            <small class="text-muted" style="font-size: 0.7rem;"><i class="${icon} me-1"></i>${cita.tipo.toUpperCase()}</small>
                        </div>
                    `;
                    ulList.appendChild(item);
                });
            } else {
                ulList.innerHTML = `
                    <div class="text-center py-5">
                        <i class="ri-checkbox-circle-line text-success fs-1 d-block mb-2"></i>
                        <span class="text-success fw-bold">Día completamente libre</span>
                    </div>`;
            }
        })
        .catch(error => {
            console.error("Error AJAX Disponibilidad:", error);
            ulList.innerHTML = '<div class="text-danger p-3">Error al cargar agenda</div>';
        });
  }

  function moverEstado(encargoId, nuevoEstado, item = null, fromColumn = null) {
    // Ya no bloqueamos con Swal de carga, hacemos el fetch directo para mayor fluidez
    fetch('/encargos/' + encargoId + '/status', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          estado: nuevoEstado
        })
      })
      .then(function(response) {
        return response.json();
      })
      .then(function(data) {
        if (data.success) {
          window.showToast(data.message, 'success');
          setTimeout(function() {
            location.reload();
          }, 800);
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error Operativo',
            text: data.message
          });
          if (item && fromColumn) fromColumn.appendChild(item); // Rollback en caso de error de base de datos
        }
      })
      .catch(function(error) {
        console.error('Error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al actualizar'
        });
      });
  }

  function abrirModalUpdatePresupuesto(id, totalActual) {
    document.getElementById('update_ppt_id').value = id;
    document.getElementById('update_ppt_total').value = totalActual;
    
    var myModal = new bootstrap.Modal(document.getElementById('modalUpdatePPT'));
    myModal.show();
  }

  function ejecutarUpdatePPT() {
    let id = document.getElementById('update_ppt_id').value;
    let total = document.getElementById('update_ppt_total').value;

    if (!total || total <= 0) {
        window.showToast('¡Debes introducir un precio válido!', 'warning');
        return;
    }

    fetch(`/presupuestos/${id}/quick-update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            total: total
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalUpdatePPT')).hide();
            window.showToast(data.message, 'success');
            setTimeout(() => location.reload(), 800);
        } else {
            window.showToast(data.message, 'error');
        }
    });
  }

  function eliminarEncargo(id) {
    Swal.fire({
      title: 'Eliminar trabajo',
      text: 'Esta accion no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Si, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(function(result) {
      if (result.isConfirmed) {
        // Ejecución silenciosa tras confirmar

        fetch('/encargos/' + id, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Content-Type': 'application/json'
            }
          })
          .then(function(response) {
            return response.json();
          })
          .then(function(data) {
            if (data.success) {
              window.showToast(data.message, 'success');
              setTimeout(function() {
                location.reload();
              }, 800);
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
              });
            }
          })
          .catch(function(error) {
            console.error('Error:', error);
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'No se pudo eliminar'
            });
          });
      }
    });
  }
</script>
@endsection