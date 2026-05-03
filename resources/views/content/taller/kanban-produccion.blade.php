@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
      <h4 class="fw-bold mb-1">Tablero de Producción</h4>
      <p class="text-muted mb-0">Control de trabajos en curso y entregas</p>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('encargos.recepcion') }}" class="btn btn-outline-primary">
        <i class="ri-phone-line me-1"></i> Ir a Recepción
      </a>
    </div>
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

                @php
                  $citaTrabajo = $encargo->citas()->where('tipo', 'trabajo')->first();
                @endphp

                @if($citaTrabajo)
                <div class="bg-white p-2 rounded mb-2 border border-success border-opacity-50 small">
                  <i class="ri-calendar-check-line text-success"></i> <strong>Cita:</strong> {{ date('d/m/Y', strtotime($citaTrabajo->fecha)) }} - {{ \Carbon\Carbon::parse($citaTrabajo->hora)->format('H:i') }}h
                </div>
                @endif

                @if($encargo->presupuesto)
                <div class="bg-white p-2 rounded mb-3 border small">
                  <span class="text-muted">Total Trabajo:</span>
                  <span class="fw-bold text-dark float-end">{{ number_format($encargo->presupuesto->total, 2) }} €</span>
                </div>
                @endif

                <div class="d-flex gap-2 flex-wrap">
                  @if($estadoKey == 'Esperando Recogida')
                  <button type="button" class="btn btn-success btn-sm flex-grow-1 fw-bold" onclick="moverEstado({{ $encargo->id }}, 'Entregado')">
                    <i class="ri-hand-heart-line me-1"></i> ENTREGAR COCHE
                  </button>
                  @endif

                  <a href="{{ route('encargos.edit', $encargo->id) }}?origin=produccion" class="btn btn-outline-primary btn-sm flex-grow-1">
                    <i class="ri-edit-line"></i> Ficha
                  </a>
                  <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarEncargo({{ $encargo->id }})">
                    <i class="ri-delete-bin-line me-1"></i> Eliminar
                  </button>
                </div>

                <div class="text-center mt-3 opacity-50">
                   <small class="text-muted"><i class="ri-arrow-left-right-line"></i> Arrastrar para mover</small>
                </div>
              </div>
            </div>
            @empty
            <div class="text-center text-muted py-5 opacity-50">
              <i class="ri-inbox-line fs-1"></i>
              <p class="mt-2 mb-0">Sin trabajos en curso</p>
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
    // Configuración del Drag & Drop para Producción
    document.querySelectorAll('.kanban-column').forEach(column => {
      new Sortable(column, {
        group: 'kanban',
        animation: 300,
        ghostClass: 'opacity-50',
        onEnd: function(evt) {
          const id = evt.item.getAttribute('data-id'),
                newEstado = evt.to.getAttribute('data-estado'),
                oldEstado = evt.item.getAttribute('data-estado-actual');

          if (oldEstado === newEstado) return;

          // Flujo lineal: Pendiente -> En Producción -> Esperando Recogida -> Entregado
          const flujoValido = {
            'Pendiente Inicio': ['En Produccion'],
            'En Produccion': ['Esperando Recogida'],
            'Esperando Recogida': ['Entregado']
          };

          if (!flujoValido[oldEstado] || !flujoValido[oldEstado].includes(newEstado)) {
             window.showToast('Movimiento denegado: el trabajo debe seguir el orden lineal.', 'warning');
             evt.from.appendChild(evt.item);
             return;
          }

          moverEstado(id, newEstado);
        }
      });
    });
  });

  // Actualiza el estado en segundo plano y avisa por Toast
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

  // Confirmación de seguridad para borrar registros
  function eliminarEncargo(id) {
    Swal.fire({
      title: '¿Eliminar trabajo?', text: 'Esta acción borrará todo el historial del coche.', icon: 'warning',
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
@endsection