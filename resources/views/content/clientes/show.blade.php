@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Clientes /</span> Ficha del Cliente
        </h4>
        <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Volver al listado
        </a>
    </div>

    <div class="row">
        <!-- Columna Izquierda: Info Cliente -->
        <div class="col-xl-4 col-lg-5 col-md-5">
            <!-- Tarjeta de Perfil -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl mx-auto mb-3">
                        <span class="avatar-initial rounded-circle bg-label-primary fs-2 fw-bold">
                            {{ strtoupper(substr($cliente->nombre, 0, 1)) }}{{ strtoupper(substr($cliente->apellido, 0, 1)) }}
                        </span>
                    </div>
                    <h4 class="mb-1 fw-bold text-dark">{{ $cliente->nombre }} {{ $cliente->apellido }}</h4>
                    <span class="badge bg-label-primary mb-3">Cliente del Taller</span>

                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <a href="tel:{{ $cliente->telefono }}" class="btn btn-primary d-flex align-items-center">
                            <i class="ri-phone-fill me-1"></i> Llamar
                        </a>
                        <a href="mailto:{{ $cliente->correo }}" class="btn btn-outline-secondary d-flex align-items-center">
                            <i class="ri-mail-line me-1"></i> Email
                        </a>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Detalles de Contacto</small>
                    <ul class="list-unstyled mb-0 mt-3">
                        <li class="d-flex align-items-center mb-3">
                            <i class="ri-user-line text-primary me-2"></i>
                            <span class="fw-bold text-dark me-2">Nombre:</span>
                            <span>{{ $cliente->nombre }} {{ $cliente->apellido }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="ri-phone-line text-primary me-2"></i>
                            <span class="fw-bold text-dark me-2">Teléfono:</span>
                            <span>{{ $cliente->telefono }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="ri-mail-line text-primary me-2"></i>
                            <span class="fw-bold text-dark me-2">Email:</span>
                            <span class="text-break">{{ $cliente->correo ?? 'No proporcionado' }}</span>
                        </li>
                    </ul>
                    <div class="d-grid mt-4">
                        <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-label-warning">Editar Datos Personales</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Vehículos y Trabajos -->
        <div class="col-xl-8 col-lg-7 col-md-7">
            <h5 class="fw-bold mb-4"><i class="ri-car-fill me-2 text-primary"></i>Vehículos Registrados ({{ $cliente->vehiculos->count() }})</h5>

            @forelse($cliente->vehiculos as $vehiculo)
            <div class="card mb-4 shadow-sm border-start border-primary border-3">
                <div class="card-header d-flex justify-content-between align-items-center bg-light bg-opacity-50">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-primary text-white me-3 rounded">
                            <i class="ri-car-line"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</h6>
                        </div>
                    </div>
                    <a href="{{ route('vehiculos.edit', $vehiculo->id) }}" class="btn btn-sm btn-icon btn-outline-primary shadow-none border-0">
                        <i class="ri-edit-line"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 py-2">Fecha</th>
                                    <th class="py-2">Trabajo Realizado</th>
                                    <th class="py-2">Estado</th>
                                    <th class="py-2 pe-3 text-end">Importe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vehiculo->encargos as $trabajo)
                                <tr>
                                    <td class="ps-3"><small class="text-muted">{{ $trabajo->created_at->format('d/m/Y') }}</small></td>
                                    <td>
                                        <a href="{{ route('encargos.show', $trabajo->id) }}" class="text-dark fw-bold">
                                            {{ Str::limit($trabajo->descripcion, 50) }}
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            $badgeColor = match($trabajo->estado) {
                                                'Entregado' => 'success',
                                                'En Produccion' => 'primary',
                                                'Cancelado' => 'danger',
                                                default => 'warning'
                                            };
                                        @endphp
                                        <span class="badge bg-label-{{ $badgeColor }} small">{{ $trabajo->estado }}</span>
                                    </td>
                                    <td class="pe-3 text-end fw-bold">
                                        {{ $trabajo->presupuesto ? number_format($trabajo->presupuesto->total, 2) . ' €' : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">Este vehículo aún no tiene trabajos registrados.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @empty
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="ri-car-washing-line fs-1 text-muted opacity-50 mb-3 d-block"></i>
                    <p class="text-muted">Este cliente no tiene ningún vehículo registrado todavía.</p>
                    <a href="{{ route('vehiculos.create', ['cliente_id' => $cliente->id]) }}" class="btn btn-primary btn-sm">Añadir Primer Vehículo</a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
