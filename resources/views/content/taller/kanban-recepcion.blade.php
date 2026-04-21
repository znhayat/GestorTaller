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
                  <a href="{{ route('presupuestos.edit', $encargo->presupuesto->id) }}" class="btn btn-warning btn-sm flex-grow-1">
                    <i class="ri-edit-line me-1"></i> Modificar
                  </a>
                  @else
                  <a href="{{ route('presupuestos.create', ['encargo_id' => $encargo->id]) }}" class="btn btn-warning btn-sm flex-grow-1">
                    <i class="ri-file-copy-line me-1"></i> Crear
                  </a>
                  @endif
                  @endif

                  @if($estadoKey == 'Presupuesto Enviado')
                  <button type="button" class="btn btn-success btn-sm flex-grow-1" onclick="mostrarModalFechaTrabajo({{ $encargo->id }})">
                    <i class="ri-check-line me-1"></i> Aceptar
                  </button>
                  <button type="button" class="btn btn-secondary btn-sm" onclick="moverEstado({{ $encargo->id }}, 'Cancelado')">
                    <i class="ri-close-line me-1"></i> Rechazar
                  </button>
                  @endif

                  <a href="{{ route('encargos.edit', $encargo->id) }}?origin=recepcion" class="btn btn-primary btn-sm" aria-label="Editar encargo de {{ $encargo->vehiculo->marca }}">
                    <i class="ri-edit-line me-1" aria-hidden="true"></i> Editar
                  </a>
                  <button type="button" class="btn btn-danger btn-sm" onclick="eliminarEncargo({{ $encargo->id }})" aria-label="Eliminar encargo de {{ $encargo->vehiculo->marca }}">
                    <i class="ri-delete-bin-line me-1" aria-hidden="true"></i> Eliminar
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
<div class="modal fade" id="modalFechaTrabajo" tabindex="-1" aria-labelledby="modalFechaTrabajoTitle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFechaTrabajoTitle">Programar Inicio del Trabajo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="encargo_id_work">
        <div class="mb-3">
          <label class="form-label" for="fecha_inicio_trabajo">Fecha de inicio del trabajo</label>
          <input type="date" id="fecha_inicio_trabajo" class="form-control" min="{{ date('Y-m-d') }}" required>
          <div class="form-text">El cliente ha aceptado el presupuesto. Programe la fecha para realizar el trabajo.</div>
        </div>
        <div class="mb-3">
          <label class="form-label" for="hora_inicio_trabajo">Hora de inicio</label>
          <input type="time" id="hora_inicio_trabajo" class="form-control" value="08:00" required>
        </div>
        <div class="mb-3">
          <label class="form-label text-success fw-bold" for="fecha_recogida_estimada"><i class="ri-calendar-check-line" aria-hidden="true"></i> Previsto Fin / Recogida</label>
          <input type="date" id="fecha_recogida_estimada" class="form-control" min="{{ date('Y-m-d') }}" required>
          <div class="form-text">Asigna el día aproximado que el coche estará terminado para entrega.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="aceptarYProgramar()">Aceptar y Programar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('vendor-script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Esperar a que el DOM esté completamente cargado
  document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando Sortable...');

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
                text: 'El proceso requiere seguir un orden lineal. No se puede retroceder de departamento ni realizar saltos.',
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
    var modal = new bootstrap.Modal(document.getElementById('modalFechaTrabajo'));
    modal.show();
  }

  function aceptarYProgramar() {
    var encargoId = document.getElementById('encargo_id_work').value;
    var fechaInicio = document.getElementById('fecha_inicio_trabajo').value;
    var horaInicio = document.getElementById('hora_inicio_trabajo').value;
    var fechaRecogida = document.getElementById('fecha_recogida_estimada').value;

    if (!fechaInicio || !fechaRecogida) {
      Swal.fire('Error', 'Debe seleccionar una fecha de inicio y una fecha prevista de recogida.', 'error');
      return;
    }

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