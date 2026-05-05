@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
      <h4 class="fw-bold mb-1">Tablero de Producción</h4>
      <p class="text-muted mb-0">Control de los coches que estamos trabajando</p>
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
                  <strong>Entrada:</strong> {{ date('d/m/Y', strtotime($citaTrabajo->fecha)) }}
                </div>
                @endif

                @if($encargo->presupuesto)
                <div class="bg-white p-2 rounded mb-3 border small">
                  <span class="text-muted">Presupuesto:</span>
                  <span class="fw-bold text-dark float-end">{{ number_format($encargo->presupuesto->total, 2) }} €</span>
                </div>
                @endif

                <div class="d-flex gap-2 flex-wrap">
                  @if($estadoKey == 'Esperando Recogida')
                  <button type="button" class="btn btn-success btn-sm flex-grow-1 fw-bold" onclick="moverEstado({{ $encargo->id }}, 'Entregado')">
                    ENTREGAR COCHE
                  </button>
                  @endif

                  <a href="{{ route('encargos.edit', $encargo->id) }}?origin=produccion" class="btn btn-outline-primary btn-sm flex-grow-1">
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
              <p class="mt-2 mb-0">Sin trabajos aquí</p>
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
    const ordenEstados = ['Pendiente Inicio', 'En Produccion', 'Esperando Recogida', 'Entregado'];

    document.querySelectorAll('.kanban-column').forEach(column => {
      new Sortable(column, {
        group: 'kanban',
        animation: 300,
        onEnd: function(evt) {
          const id = evt.item.getAttribute('data-id'),
                newEstado = evt.to.getAttribute('data-estado'),
                oldEstado = evt.item.getAttribute('data-estado-actual');

          if (oldEstado === newEstado) return;

          const oldIdx = ordenEstados.indexOf(oldEstado);
          const newIdx = ordenEstados.indexOf(newEstado);

          // Si tiran hacia atrás, pedimos confirmación por si acaso
          if (newIdx < oldIdx) {
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
                evt.from.appendChild(evt.item);
              }
            });
            return;
          }

          // Para que sigan el orden
          const flujoValido = {
            'Pendiente Inicio': ['En Produccion'],
            'En Produccion': ['Esperando Recogida'],
            'Esperando Recogida': ['Entregado']
          };

          if (flujoValido[oldEstado] && !flujoValido[oldEstado].includes(newEstado)) {
             window.showToast('Sigue el orden del taller', 'warning');
             evt.from.appendChild(evt.item);
             return;
          }

          moverEstado(id, newEstado);
        }
      });
    });
  });

  // Mandamos el cambio al servidor
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

  function eliminarEncargo(id) {
    if(confirm('¿Seguro que quieres borrar este trabajo?')) {
        fetch('/encargos/' + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }})
        .then(() => location.reload());
    }
  }
</script>
@endsection