@extends('layouts/contentNavbarLayout')

@section('title', 'Programar Cita')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Citas /</span> Nueva Cita</h4>

<div class="card">
  <div class="card-header">
    <h5>Programar Nueva Cita</h5>
  </div>
  <div class="card-body">
    {{-- Formulario de creación: apunta al método 'store' del controlador --}}
    <form action="{{ route('citas.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label class="form-label">Seleccionar Trabajo (Encargo)</label>
        {{-- Listado de encargos: mostramos coche, cliente y una breve descripción del problema --}}
        <select name="encargo_id" class="form-select" required>
          <option value="">-- Selecciona el vehículo y cliente --</option>
          @foreach($encargos as $e)
          <option value="{{ $e->id }}">
            {{ $e->vehiculo->marca }} {{ $e->vehiculo->modelo }} - {{ $e->vehiculo->cliente->nombre }}
            ({{ Str::limit($e->descripcion, 30) }})
          </option>
          @endforeach
        </select>
      </div>

      <div class="row">
        {{-- Campo de fecha: por defecto he puesto la fecha del día actual usando date() --}}
        <div class="mb-3 col-md-6">
          <label class="form-label">Fecha</label>
          <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        {{-- Campo de hora: HTML5 nos facilita el selector de tiempo --}}
        <div class="mb-3 col-md-6">
          <label class="form-label">Hora</label>
          <input type="time" name="hora" class="form-control" required>
        </div>
      </div>

      <!-- Panel de disponibilidad de la Agenda -->
      <div class="card bg-lighter mb-4 mt-3 border shadow-none">
        <div class="card-body p-3">
            <h6 class="card-title fw-bold text-secondary mb-2"><i class="ri-calendar-event-line me-1"></i> Agenda para el día seleccionado</h6>
            <div id="agenda-preview-container" class="small text-muted">
              Cargando disponibilidad...
            </div>
        </div>
      </div>

      <div class="mt-3">
        {{-- Botón principal con icono de calendario para hacerlo más visual --}}
        <button type="submit" class="btn btn-primary me-2">
          <i class="ri-calendar-check-line me-1"></i> Confirmar Cita
        </button>
        {{-- Enlace para volver atrás si el usuario se arrepiente --}}
        <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.querySelector('input[name="fecha"]');
    const agendaPreview = document.getElementById('agenda-preview-container');

    function checkAvailability() {
        const date = fechaInput.value;
        if (!date) return;
        
        agendaPreview.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Comprobando disponibilidad...';
        
        fetch(`/api/disponibilidad?date=${date}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    agendaPreview.innerHTML = '<span class="text-success fw-bold"><i class="ri-checkbox-circle-line me-1"></i> Todo el día está libre.</span> Puedes elegir cualquier hora.';
                } else {
                    let html = '<span class="text-warning fw-bold mb-2 d-block"><i class="ri-error-warning-line me-1"></i> Horarios ocupados:</span>';
                    html += '<ul class="mb-1 ps-3">';
                    data.forEach(item => {
                        html += `<li><strong>${item.hora}h</strong> - ${item.titulo}</li>`;
                    });
                    html += '</ul><span class="text-success"><i class="ri-information-line me-1"></i> El resto del horario está libre.</span>';
                    agendaPreview.innerHTML = html;
                }
            })
            .catch(err => {
                agendaPreview.innerHTML = '<span class="text-danger">Error al consultar la agenda. Inténtalo de nuevo.</span>';
            });
    }

    if (fechaInput) {
        fechaInput.addEventListener('change', checkAvailability);
        checkAvailability();
    }
});
</script>
@endsection