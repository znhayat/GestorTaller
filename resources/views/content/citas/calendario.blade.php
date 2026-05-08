@extends('layouts/contentNavbarLayout')

@section('title', 'Calendario de Taller')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Control de Ocupación Mensual</h5>
        <a href="{{ route('trabajo.create') }}" class="btn btn-primary btn-sm"><i class="ri-add-line"></i> Nueva Cita de Recepción</a>
      </div>
      <div class="card-body">
        <div id="calendar" style="min-height: 700px;"></div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('vendor-script')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales/es.global.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'es',
      themeSystem: 'bootstrap5',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
      },
      navLinks: true, // can click day/week names to navigate views
      dayMaxEvents: true,
      hiddenDays: [0, 6], // Ocultar Sábado (6) y Domingo (0)
      events: '/api/eventos',
      eventClick: function(info) {
        if (info.event.url) {
          info.jsEvent.preventDefault(); // Previene abrir como una URL normal del navegador
          window.location.href = info.event.url;
        }
      }
    });
    calendar.render();
  });
</script>
@endsection
