@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
      <h4 class="fw-bold mb-1">Tablero de Produccion</h4>
      <p class="text-muted mb-0">Arrastre las tarjetas para avanzar el trabajo</p>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('encargos.recepcion') }}" class="btn btn-outline-primary">
        <i class="ri-phone-line me-1"></i> Ir a Recepcion
      </a>
      <a href="{{ route('encargos.index') }}" class="btn btn-outline-secondary">
        <i class="ri-list-view me-1"></i> Vista Lista
      </a>
    </div>
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

                @php
                $citaTrabajo = $encargo->citas()->where('tipo', 'trabajo')->first();
                @endphp

                @if($citaTrabajo)
                <div class="alert alert-success py-2 px-2 mb-2 small">
                  <i class="ri-calendar-check-line me-1"></i> <strong>Cita de trabajo:</strong> {{ date('d/m/Y', strtotime($citaTrabajo->fecha)) }} - {{ \Carbon\Carbon::parse($citaTrabajo->hora)->format('H:i') }}h
                </div>
                @elseif($encargo->fecha_inicio_trabajo)
                <div class="alert alert-success py-2 px-2 mb-2 small">
                  <i class="ri-calendar-check-line me-1"></i> <strong>Inicio programado:</strong> {{ date('d/m/Y', strtotime($encargo->fecha_inicio_trabajo)) }} - {{ \Carbon\Carbon::parse($encargo->hora_inicio_trabajo)->format('H:i') }}h
                </div>
                @endif

                @if($encargo->fecha_entrada && $estadoKey == 'En Produccion')
                <small class="text-muted d-block mb-2"><i class="ri-calendar-line me-1"></i> <strong>Entrada taller:</strong> {{ date('d/m/Y', strtotime($encargo->fecha_entrada)) }}</small>
                @endif

                @if($encargo->presupuesto)
                <div class="alert alert-info py-2 px-2 mb-3 small">
                  <strong>Total:</strong> {{ number_format($encargo->presupuesto->total, 2) }} €
                </div>
                @endif

                <div class="d-flex gap-2 flex-wrap">
                  @if($estadoKey == 'Esperando Recogida')
                  <button type="button" class="btn btn-success btn-sm flex-grow-1" onclick="moverEstado({{ $encargo->id }}, 'Entregado')">
                    <i class="ri-hand-heart-line me-1"></i> Entregar y Facturar
                  </button>
                  @endif

                  <a href="{{ route('encargos.edit', $encargo->id) }}" class="btn btn-primary btn-sm">
                    <i class="ri-edit-line me-1"></i> Editar
                  </a>
                  <button type="button" class="btn btn-danger btn-sm" onclick="eliminarEncargo({{ $encargo->id }})">
                    <i class="ri-delete-bin-line me-1"></i> Eliminar
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
@endsection

@section('vendor-script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando Sortable...');

    var columns = document.querySelectorAll('.kanban-column');
    console.log('Columnas encontradas:', columns.length);

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

          if (newEstado && encargoId && newEstado !== oldEstado) {
            moverEstado(encargoId, newEstado);
          } else if (newEstado !== oldEstado) {
            Swal.fire({
              icon: 'warning',
              title: 'Movimiento no permitido',
              text: 'No puede mover esta tarjeta a esa columna',
              confirmButtonText: 'Entendido'
            });
            location.reload();
          }
        }
      });
    });
  });

  function moverEstado(encargoId, nuevoEstado) {
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