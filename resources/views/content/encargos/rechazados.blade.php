@extends('layouts/contentNavbarLayout')

@section('title', 'Presupuestos Rechazados')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><span class="text-muted fw-light">Taller /</span> Historial de Rechazos</h4>
            <p class="text-muted mb-0">Base de datos de todos los presupuestos y encargos cancelados.</p>
        </div>
    </div>

    <!-- Buscador -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('encargos.rechazados') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Buscar por cliente, matrícula, modelo o número de expediente..." value="{{ $search }}">
                <button type="submit" class="btn btn-primary"><i class="ri-search-line"></i> Buscar</button>
                @if($search)
                <a href="{{ route('encargos.rechazados') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Trabajos Cancelados (Archivados)</h5>
            <button class="btn btn-sm btn-outline-success" id="btnExportCSV"><i class="ri-file-excel-2-line me-1"></i> Exportar CSV</button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="tableExportable">
                <thead class="table-light">
                    <tr>
                        <th>Nº Exp / PR</th>
                        <th>Cliente</th>
                        <th>Vehículo</th>
                        <th>Estado</th>
                        <th>Fecha Cierre</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($encargos as $encargo)
                    <tr>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-primary">#EXP-{{ str_pad($encargo->id, 5, '0', STR_PAD_LEFT) }}</span>
                                @if($encargo->presupuesto)
                                <small class="text-danger"><i class="ri-money-dollar-circle-line"></i> PR-{{ $encargo->presupuesto->id }} ({{ number_format($encargo->presupuesto->total, 2) }} €)</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-medium">{{ $encargo->vehiculo->cliente->nombre }} {{ $encargo->vehiculo->cliente->apellido }}</span>
                                <small class="text-muted">{{ $encargo->vehiculo->cliente->telefono }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span>{{ $encargo->vehiculo->marca }} {{ $encargo->vehiculo->modelo }}</span>
                                <small class="text-muted"><i class="ri-car-fill px-1"></i> {{ $encargo->vehiculo->matricula }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-label-danger py-2">
                                <i class="ri-forbid-line me-1"></i> {{ strtoupper($encargo->estado) }}
                            </span>
                        </td>
                        <td>
                            {{ $encargo->updated_at->format('d/m/Y') }}
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('encargos.edit', $encargo->id) }}" class="btn btn-sm btn-primary" title="Abrir y Revisar Expediente">
                                    <i class="ri-folder-open-line me-1"></i> Ficha
                                </a>
                                <button type="button" class="btn btn-sm btn-success" onclick="restaurarCaso({{ $encargo->id }})" title="Restaurar y Reabrir en Recepción">
                                    <i class="ri-refresh-line me-1"></i> Restaurar
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="eliminarDefinitivo({{ $encargo->id }})" title="Borrado Permanente">
                                    <i class="ri-delete-bin-line me-1"></i> Borrar
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="ri-inbox-archive-line fs-2 d-block mb-2 text-primary"></i>
                            No hay registros de presupuestos rechazados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{ $encargos->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function restaurarCaso(id) {
        Swal.fire({
            title: '¿Reabrir Expediente?',
            text: 'El caso volverá al panel de Recepción en estado "En Revisión".',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, restaurar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Ejecución directa
                fetch(`/encargos/${id}/status`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ estado: 'En Revision' })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        window.showToast('El expediente ha sido devuelto a Recepción.', 'success');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    }

    function eliminarDefinitivo(id) {
        Swal.fire({
            title: 'Borrado Definitivo',
            text: 'Esta acción borrará el registro del caso permanentemente.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Ejecución directa
                fetch(`/encargos/${id}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        window.showToast('El registro se ha borrado correctamente.', 'success');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    }
</script>
@endsection
