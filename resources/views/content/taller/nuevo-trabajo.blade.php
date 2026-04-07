@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="ri-add-circle-line me-2"></i> Nuevo Trabajo</h4>
    <a href="{{ route('encargos.recepcion') }}" class="btn btn-secondary">
      <i class="ri-arrow-left-line me-1"></i> Volver al Kanban
    </a>
  </div>

  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">Datos del Trabajo</h5>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('trabajo.store') }}">
        @csrf

        <!-- Datos del Cliente -->
        <h6 class="mb-3 text-primary"><i class="ri-user-line me-1"></i> Datos del Cliente</h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido') }}" required>
            @error('apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}" required>
            @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}" required>
            @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <!-- Datos del Vehículo -->
        <h6 class="mb-3 text-primary mt-3"><i class="ri-car-line me-1"></i> Datos del Vehículo</h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Marca</label>
            <input type="text" name="marca" class="form-control @error('marca') is-invalid @enderror" value="{{ old('marca') }}" required>
            @error('marca') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Modelo</label>
            <input type="text" name="modelo" class="form-control @error('modelo') is-invalid @enderror" value="{{ old('modelo') }}" required>
            @error('modelo') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <!-- Descripción -->
        <div class="mb-3">
          <label class="form-label">Descripción del trabajo</label>
          <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3" required>{{ old('descripcion') }}</textarea>
          @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Presupuesto Inicial -->
        <h6 class="mb-3 text-primary mt-3"><i class="ri-money-euro-circle-line me-1"></i> Presupuesto Inicial</h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Precio Materiales (€)</label>
            <input type="number" step="0.01" name="precio_materiales" class="form-control @error('precio_materiales') is-invalid @enderror" value="{{ old('precio_materiales', 0) }}" required>
            @error('precio_materiales') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Precio Horas (€)</label>
            <input type="number" step="0.01" name="precio_horas" class="form-control @error('precio_horas') is-invalid @enderror" value="{{ old('precio_horas', 0) }}" required>
            @error('precio_horas') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="alert alert-info">
          <i class="ri-information-line me-1"></i>
          El trabajo aparecerá en el <strong>Tablero de Recepción</strong> en la columna <strong>"Cita Agendada"</strong>.
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="ri-save-line me-1"></i> Guardar Trabajo
        </button>
        <a href="{{ route('encargos.recepcion') }}" class="btn btn-secondary">
          Cancelar
        </a>
      </form>
    </div>
  </div>
</div>
@endsection