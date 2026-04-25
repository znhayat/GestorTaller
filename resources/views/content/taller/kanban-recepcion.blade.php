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

                @if($encargo->presupuesto && $estadoKey == 'En Revision')
                <div class="alert alert-info py-2 px-2 mb-3 small">
                  <strong>Presupuesto:</strong> {{ number_format($encargo->presupuesto->total, 2) }} €
                </div>
                @endif

                <div class="d-flex gap-2 flex-wrap">
                  @if($estadoKey == 'En Revision')
                  @if($encargo->presupuesto)
                  <a href="{{ route('presupuestos.edit', $encargo->presupuesto->id) }}" class="btn btn-warning btn-sm flex-grow-1" title="Modificar Presupuesto">
                    <i class="ri-edit-line me-1"></i> Modif. PPT
                  </a>
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

<!-- Modal para programar fecha de inicio -->
<div class="modal fade" id="modalFechaTrabajo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded border-0" style="box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
      <div class="modal-header border-bottom-0 pb-3">
        <div class="d-flex align-items-center">
            <div class="avatar avatar-md me-3">
                <span class="avatar-initial bg-label-success rounded-circle"><i class="ri-calendar-check-line fs-4"></i></span>
            </div>
            <div>
                <h5 class="modal-title fw-bold mb-0">Confirmar Cita de Taller</h5>
                <small class="text-muted">Aceptación Oficial de Presupuesto</small>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body pt-2">
        <input type="hidden" id="encargo_id_work">
        
        <div class="row mt-2 g-4 mb-4">
            <!-- Ingreso -->
            <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" id="fecha_inicio_trabajo" placeholder="Elige fecha y hora">
                    <label for="fecha_inicio_trabajo" class="text-primary fw-medium"><i class="ri-login-circle-line me-1"></i> Ingreso al Taller</label>
                </div>
            </div>
            <!-- Salida -->
            <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" id="fecha_recogida_estimada" placeholder="Día estimado">
                    <label for="fecha_recogida_estimada" class="text-success fw-medium"><i class="ri-logout-circle-line me-1"></i> Estimación Entrega</label>
                </div>
            </div>
        </div>

        <!-- Módulo Dinámico de Disponibilidad Ocupada -->
        <div id="disponibilidadContenedor" class="alert alert-secondary d-none mb-4" style="border-left: 4px solid var(--bs-secondary);">
            <div class="d-flex align-items-center mb-2">
                <i class="ri-calendar-event-fill text-dark me-2 fs-5"></i>
                <h6 class="mb-0 fw-bold text-dark">Agenda de este día</h6>
            </div>
            <ul id="listaOcupadas" class="mb-0 ps-3 small text-muted font-monospace" style="list-style-type: square;">
                <!-- Rellenado por JS -->
            </ul>
        </div>

        <div class="alert alert-primary d-flex align-items-center mb-0" role="alert" style="border-left: 4px solid var(--bs-primary);">
            <i class="ri-information-line me-3 fs-4"></i>
            <div class="small">
                Al certificar las fechas, el expediente será inyectado directamente en la cadena de <strong>Producción (Taller)</strong>.
            </div>
        </div>
      </div>
      <div class="modal-footer border-top-0 pt-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Posponer</button>
        <button type="button" class="btn btn-success fw-bold px-4 shadow-sm" onclick="aceptarYProgramar()">Inyectar al Taller</button>
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
             Swal.fire({
                icon: 'warning',
                title: 'Movimiento Denegado',
                text: 'El proceso requiere seguir un orden secuencial lógico o la tarjeta se encuentra en estado terminal.',
                confirmButtonText: 'Entendido'
             });
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

    Swal.fire({
      title: 'Actualizando...',
      allowOutsideClick: false,
      didOpen: function() {
        Swal.showLoading();
      }
    });

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
          Swal.fire({
            icon: 'success',
            title: 'Actualizado',
            text: data.message,
            timer: 1500,
            showConfirmButton: false
          });
          setTimeout(function() {
            location.reload();
          }, 1500);
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
      let divC = document.getElementById('disponibilidadContenedor');
      let ulList = document.getElementById('listaOcupadas');
      
      divC.classList.add('d-none');
      ulList.innerHTML = '';

      fetch('/api/disponibilidad?date=' + fechaIso)
        .then(response => response.json())
        .then(ocupadas => {
            if(ocupadas && ocupadas.length > 0) {
                ocupadas.forEach(cita => {
                    let li = document.createElement('li');
                    li.innerHTML = `<strong class="text-dark">${cita.hora}h</strong> - ${cita.titulo}`;
                    ulList.appendChild(li);
                });
                divC.classList.remove('d-none');
            } else {
                let li = document.createElement('li');
                li.innerHTML = '<span class="text-success"><i class="ri-check-line"></i> Día completamente libre de citas</span>';
                ulList.appendChild(li);
                divC.classList.remove('d-none');
            }
        })
        .catch(error => console.error("Error AJAX Disponibilidad:", error));
  }

  function moverEstado(encargoId, nuevoEstado, item = null, fromColumn = null) {
    Swal.fire({
      title: 'Actualizando...',
      allowOutsideClick: false,
      didOpen: function() {
        Swal.showLoading();
      }
    });

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
          Swal.fire({
            icon: 'success',
            title: 'Actualizado',
            text: data.message,
            timer: 1500,
            showConfirmButton: false
          });
          setTimeout(function() {
            location.reload();
          }, 1500);
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
        Swal.fire({
          title: 'Eliminando...',
          allowOutsideClick: false,
          didOpen: function() {
            Swal.showLoading();
          }
        });

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
              Swal.fire({
                icon: 'success',
                title: 'Eliminado',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
              });
              setTimeout(function() {
                location.reload();
              }, 1500);
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