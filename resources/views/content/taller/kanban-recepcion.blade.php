@extends('layouts/contentNavbarLayout')

@section('vendor-style')
<style>
  .kanban-column {
    background: #f8f9fa;
    border-radius: 0.75rem;
    min-height: 550px;
    transition: all 0.2s;
  }

  .kanban-item {
    cursor: grab;
    transition: all 0.2s;
    border-left: 4px solid;
  }

  .kanban-item:active {
    cursor: grabbing;
  }

  .kanban-item.dragging {
    opacity: 0.4;
  }

  .kanban-column.drag-over {
    background: #e9ecef;
    border: 2px dashed #696cff;
    transform: scale(1.01);
  }

  .card-eliminando {
    animation: fadeOut 0.3s ease-out forwards;
  }

  @keyframes fadeOut {
    to {
      opacity: 0;
      transform: translateX(-100px);
    }
  }

  /* Estilos mejorados para botones */
  .btn-action {
    transition: all 0.2s;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
  }

  .btn-action:hover {
    transform: translateY(-1px);
  }

  .card-header-custom {
    border-radius: 0.75rem 0.75rem 0 0;
  }

  /* Scroll personalizado */
  .kanban-column::-webkit-scrollbar {
    width: 6px;
  }

  .kanban-column::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
  }

  .kanban-column::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
  }

  .kanban-column::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
  }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Cabecera -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">
        <i class="ri-phone-line me-2 text-primary"></i> Tablero de Recepción
      </h4>
      <p class="text-muted mb-0">Arrastra las tarjetas para cambiar el estado</p>
    </div>
    <a href="{{ route('trabajo.create') }}" class="btn btn-primary">
      <i class="ri-add-line me-1"></i> Nuevo Trabajo
    </a>
  </div>

  <!-- Columnas Kanban -->
  <div class="row g-4">
    @foreach($estados as $estadoKey => $config)
    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <!-- Cabecera de columna -->
        <div class="card-header {{ $config['bg'] }} text-white py-3 card-header-custom">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-0 text-white">{{ $config['title'] }}</h5>
              <small class="text-white-50">{{ $config['description'] }}</small>
            </div>
            <span class="badge bg-white text-dark rounded-pill fs-6 px-3 py-1">
              {{ $encargos->where('estado', $estadoKey)->count() }}
            </span>
          </div>
        </div>

        <!-- Contenedor de tarjetas -->
        <div class="kanban-column p-3" data-estado="{{ $estadoKey }}" style="min-height: 500px; max-height: 600px; overflow-y: auto;">

          @foreach($encargos->where('estado', $estadoKey) as $encargo)
          <div class="card kanban-item mb-3 shadow-sm border-start border-{{ $config['color'] }} border-3"
            data-id="{{ $encargo->id }}"
            data-estado-actual="{{ $estadoKey }}">
            <div class="card-body p-3">
              <!-- Info vehículo -->
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <h6 class="mb-0 fw-bold">
                    <i class="ri-car-line me-1 text-{{ $config['color'] }}"></i>
                    {{ $encargo->vehiculo->marca }} {{ $encargo->vehiculo->modelo }}
                  </h6>
                  <small class="text-muted">
                    <i class="ri-user-line me-1"></i>{{ $encargo->vehiculo->cliente->nombre }} {{ $encargo->vehiculo->cliente->apellido }}
                  </small>
                </div>
                <span class="badge bg-{{ $config['color'] }}">{{ $config['title'] }}</span>
              </div>

              <!-- Contacto -->
              <div class="mb-2">
                <small class="text-muted d-flex align-items-center gap-1">
                  <i class="ri-phone-line"></i> {{ $encargo->vehiculo->cliente->telefono }}
                </small>
                <small class="text-muted d-flex align-items-center gap-1">
                  <i class="ri-mail-line"></i> {{ $encargo->vehiculo->cliente->correo }}
                </small>
                @if($encargo->cita_revision)
                <small class="text-primary d-flex align-items-center gap-1 mt-1">
                  <i class="ri-calendar-line"></i> Cita: {{ date('d/m/Y', strtotime($encargo->cita_revision)) }}
                </small>
                @endif
              </div>

              <!-- Descripción -->
              <div class="bg-light rounded p-2 mb-2">
                <small class="text-secondary d-flex">
                  <i class="ri-file-text-line me-1"></i>
                  {{ Str::limit($encargo->descripcion, 80) }}
                </small>
              </div>

              <!-- Presupuesto -->
              @if($encargo->presupuesto)
              <div class="alert alert-info py-1 px-2 mb-2 small">
                <div class="d-flex justify-content-between align-items-center">
                  <span>
                    <i class="ri-money-euro-circle-line me-1"></i>
                    <strong>Total:</strong> {{ number_format($encargo->presupuesto->total, 2) }}€
                  </span>
                  @if($encargo->presupuesto->aceptado)
                  <span class="badge bg-success">Aceptado</span>
                  @endif
                </div>
                <small>Materiales: {{ number_format($encargo->presupuesto->precio_materiales, 2) }}€ | Horas: {{ number_format($encargo->presupuesto->precio_horas, 2) }}€</small>
              </div>
              @endif

              <!-- Botones de acción -->
              <div class="d-flex gap-2 mt-2">
                @if($estadoKey == 'Cita Agendada')
                <button class="btn btn-info btn-action flex-grow-1" onclick="moverEstado({{ $encargo->id }}, 'En Revision')">
                  <i class="ri-tools-line me-1"></i> Iniciar Revisión
                </button>
                @endif

                @if($estadoKey == 'En Revision')
                @if($encargo->presupuesto)
                <a href="{{ route('presupuestos.edit', $encargo->presupuesto->id) }}" class="btn btn-warning btn-action flex-grow-1">
                  <i class="ri-edit-line me-1"></i> Modificar Presupuesto
                </a>
                @else
                <a href="{{ route('presupuestos.create', ['encargo_id' => $encargo->id]) }}" class="btn btn-warning btn-action flex-grow-1">
                  <i class="ri-file-copy-line me-1"></i> Crear Presupuesto
                </a>
                @endif
                @endif

                @if($estadoKey == 'Presupuesto Enviado')
                <button class="btn btn-success btn-action flex-grow-1" onclick="moverEstado({{ $encargo->id }}, 'Pendiente Inicio')">
                  <i class="ri-check-line me-1"></i> Aceptar
                </button>
                <button class="btn btn-danger btn-action flex-grow-1" onclick="moverEstado({{ $encargo->id }}, 'Cancelado')">
                  <i class="ri-close-line me-1"></i> Cancelar
                </button>
                @endif

                <!-- Botón Editar - Ahora con estilo visible -->
                <a href="{{ route('encargos.edit', $encargo->id) }}"
                  class="btn btn-outline-secondary btn-action"
                  title="Editar encargo"
                  style="background-color: white;">
                  <i class="ri-edit-line me-1"></i> Editar
                </a>
              </div>
            </div>
          </div>
          @endforeach

          <!-- Mensaje vacío -->
          @if($encargos->where('estado', $estadoKey)->count() == 0)
          <div class="text-center text-muted py-5">
            <i class="ri-inbox-line fs-1"></i>
            <p class="mb-0 mt-2">No hay elementos</p>
          </div>
          @endif
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection

@section('vendor-script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const columns = document.querySelectorAll('.kanban-column');

    columns.forEach(column => {
      new Sortable(column, {
        group: {
          name: 'kanban',
          pull: true,
          revertClone: false
        },
        animation: 200,
        ghostClass: 'dragging',
        onEnd: function(evt) {
          const item = evt.item;
          const newEstado = evt.to.getAttribute('data-estado');
          const encargoId = item.getAttribute('data-id');
          const oldEstado = item.getAttribute('data-estado-actual');

          if (newEstado && encargoId && newEstado !== oldEstado) {
            moverEstado(encargoId, newEstado);
          }
        }
      });
    });
  });

  function moverEstado(encargoId, nuevoEstado) {
    Swal.fire({
      title: 'Actualizando...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });

    fetch(`/encargos/${encargoId}/status`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          estado: nuevoEstado
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          if (data.cancelado) {
            Swal.fire({
              icon: 'success',
              title: 'Cancelado',
              text: 'El trabajo ha sido cancelado',
              timer: 1500,
              showConfirmButton: false
            });
            setTimeout(() => location.reload(), 1500);
          } else {
            Swal.fire({
              icon: 'success',
              title: 'Actualizado',
              text: data.message,
              timer: 1500,
              showConfirmButton: false
            });
            setTimeout(() => location.reload(), 1500);
          }
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message
          });
        }
      })
      .catch(() => {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al actualizar'
        });
      });
  }
</script>
@endsection