@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="ri-add-circle-line me-2"></i> Nuevo Trabajo</h4>
    <a href="{{ route('encargos.recepcion') }}" class="btn btn-secondary"><i class="ri-arrow-left-line me-1"></i> Volver</a>
  </div>

  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">Datos del Trabajo</h5>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('trabajo.store') }}">
        @csrf

        <h6 class="mb-3 text-primary"><i class="ri-user-line me-1"></i> Datos del Cliente</h6>
        <div class="row">
        </div>

        <div class="row">
          <div class="col-md-6 mb-3"><label class="form-label" for="trabajo-nombre">Nombre</label><input type="text" id="trabajo-nombre" name="nombre" class="form-control" required></div>
          <div class="col-md-6 mb-3"><label class="form-label" for="trabajo-apellido">Apellido</label><input type="text" id="trabajo-apellido" name="apellido" class="form-control" required></div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3"><label class="form-label" for="trabajo-telefono">Telefono</label><input type="text" id="trabajo-telefono" name="telefono" class="form-control" required></div>
          <div class="col-md-6 mb-3"><label class="form-label" for="trabajo-correo">Correo</label><input type="email" id="trabajo-correo" name="correo" class="form-control" required></div>
        </div>

        <h6 class="mb-3 text-primary mt-3"><i class="ri-car-line me-1"></i> Datos del Vehiculo</h6>
        <div class="row">
          <div class="col-md-6 mb-3"><label class="form-label" for="trabajo-marca">Marca</label><input type="text" id="trabajo-marca" name="marca" class="form-control" required></div>
          <div class="col-md-6 mb-3"><label class="form-label" for="trabajo-modelo">Modelo</label><input type="text" id="trabajo-modelo" name="modelo" class="form-control" required></div>
        </div>

        <div class="mb-3"><label class="form-label" for="trabajo-descripcion">Descripcion</label><textarea id="trabajo-descripcion" name="descripcion" class="form-control" rows="3" required></textarea></div>

        <h6 class="mb-3 text-primary mt-3"><i class="ri-calendar-line me-1"></i> Cita de Revision</h6>
        <div class="row">
          <div class="col-md-6 mb-3"><label class="form-label" for="trabajo-fecha">Fecha</label><input type="date" id="trabajo-fecha" name="cita_revision" class="form-control" value="{{ date('Y-m-d', strtotime('+1 days')) }}" required></div>
          <div class="col-md-6 mb-3"><label class="form-label" for="trabajo-hora">Hora</label><input type="time" id="trabajo-hora" name="hora_cita" class="form-control" value="09:00" required></div>
        </div>

        <h6 class="mb-3 text-primary mt-3"><i class="ri-money-euro-circle-line me-1"></i> Presupuesto Inicial</h6>
        <div class="row">
          <div class="col-md-6 mb-3"><label class="form-label" for="trabajo-materiales">Materiales (€)</label><input type="number" id="trabajo-materiales" step="0.01" name="precio_materiales" class="form-control" value="0" required></div>
          <div class="col-md-6 mb-3"><label class="form-label" for="trabajo-horas">Horas (€)</label><input type="number" id="trabajo-horas" step="0.01" name="precio_horas" class="form-control" value="0" required></div>
        </div>

        <div class="alert alert-info">El trabajo aparecera en el Tablero de Recepcion en la columna "Cita Agendada".</div>

        <button type="submit" class="btn btn-primary"><i class="ri-save-line me-1"></i> Guardar</button>
        <a href="{{ route('encargos.recepcion') }}" class="btn btn-secondary">Cancelar</a>
      </form>
    </div>
  </div>
</div>
@endsection