@extends('layouts/contentNavbarLayout')

@section('title', 'Gestión de Usuarios')

@section('content')
<style>
    .search-input-width { width: 250px; }
    .role-select-width { width: 100px; }
    .pass-input-width { width: 110px; }
    @media (max-width: 768px) {
        .search-input-width { width: 100% !important; }
    }
</style>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h5 class="mb-0">Administración de Usuarios</h5>
        <p class="text-muted mb-0 small">Gestiona los permisos y accesos de los trabajadores del taller.</p>
    </div>
    <div class="d-flex align-items-center">
        <div class="input-group input-group-merge search-input-width">
            <span class="input-group-text"><i class="ri-search-line text-primary"></i></span>
            <input type="text" id="buscar-trabajador" class="form-control" placeholder="Buscar trabajador..." aria-label="Buscar trabajador">
        </div>
    </div>
  </div>

  @if(session('success'))
  <div class="alert alert-success mx-4">
    {{ session('success') }}
  </div>
  @endif

  @if(session('error'))
  <div class="alert alert-danger mx-4">
    {{ session('error') }}
  </div>
  @endif

    <div class="table-responsive text-nowrap">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Identidad</th>
            <th>Permisos / Seguridad</th>
            <th>Estado</th>
            <th>Registro</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($usuarios as $usuario)
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm bg-label-primary me-3">
                  <span class="avatar-initial rounded-circle">{{ strtoupper(substr($usuario->name, 0, 1)) }}</span>
                </div>
                <div>
                  <h6 class="mb-0 fw-bold text-dark">{{ $usuario->name }}</h6>
                  <small class="text-muted">{{ $usuario->email }}</small>
                </div>
              </div>
            </td>
            <td>
              <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" class="row g-2 align-items-center mb-0">
                @csrf
                @method('PUT')
                <div class="col-auto">
                  <select name="role" class="form-select form-select-sm role-select-width">
                    <option value="user" {{ $usuario->role === 'user' ? 'selected' : '' }}>USER</option>
                    <option value="admin" {{ $usuario->role === 'admin' ? 'selected' : '' }}>ADMIN</option>
                  </select>
                </div>
                <div class="col-auto">
                  <input type="password" name="password" class="form-control form-control-sm pass-input-width" placeholder="Nueva clave">
                </div>
            </td>
            <td>
                <div class="form-check form-switch mb-0">
                  <input type="hidden" name="is_approved" value="0">
                  <input class="form-check-input" type="checkbox" role="switch" name="is_approved" value="1" id="aprobado_{{ $usuario->id }}" {{ $usuario->is_approved ? 'checked' : '' }}>
                  <label class="form-check-label small" for="aprobado_{{ $usuario->id }}">
                    {{ $usuario->is_approved ? 'Aprobado' : 'Bloqueado' }}
                  </label>
                </div>
            </td>
            <td><small class="text-muted">{{ $usuario->created_at->format('d/m/Y') }}</small></td>
            <td class="text-center">
                <button type="submit" class="btn btn-sm btn-primary me-2" title="Guardar Cambios">
                  <i class="ri-save-line"></i>
                </button>
              </form>
              
              <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="d-inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que deseas eliminar definitivamente a este usuario?')" title="Eliminar Usuario">
                  <i class="ri-delete-bin-line"></i>
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  
  @if($usuarios->hasPages())
  <div class="card-footer">
    {{ $usuarios->links() }}
  </div>
  @endif
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('buscar-trabajador');
    const tableRows = document.querySelectorAll('tbody tr');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();

            tableRows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endsection
