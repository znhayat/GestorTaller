@extends('layouts/contentNavbarLayout')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Administración de Usuarios</h5>
    <p class="text-muted mb-0 small">Los usuarios pendientes no podrán acceder al Taller hasta que se les asigne el ticket de "Aprobado".</p>
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
    <table class="table table-hover" aria-label="Lista de usuarios">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Rol</th>
          <th>Aprobado</th>
          <th>Fecha Registro</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($usuarios as $usuario)
        <tr>
          <td><strong>{{ $usuario->name }}</strong></td>
          <td>{{ $usuario->email }}</td>
          <td>
            <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" class="d-flex align-items-center mb-0">
              @csrf
              @method('PUT')
              <select name="role" class="form-select form-select-sm" aria-label="Seleccionar rol para {{ $usuario->name }}" style="width: 120px;">
                <option value="user" {{ $usuario->role === 'user' ? 'selected' : '' }}>Usuario</option>
                <option value="admin" {{ $usuario->role === 'admin' ? 'selected' : '' }}>Admin</option>
              </select>
          </td>
          <td>
              <div class="form-check form-switch mb-0">
                {{-- Truco HTML para mandar 0 si no se marca el Checkbox --}}
                <input type="hidden" name="is_approved" value="0">
                <input class="form-check-input" type="checkbox" role="switch" name="is_approved" value="1" id="aprobado_{{ $usuario->id }}" {{ $usuario->is_approved ? 'checked' : '' }} aria-label="Aprobar usuario {{ $usuario->name }}">
                <label class="form-check-label visually-hidden" for="aprobado_{{ $usuario->id }}">Aprobar usuario</label>
              </div>
          </td>
          <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
          <td>
              <button type="submit" class="btn btn-sm btn-success me-2" aria-label="Guardar cambios de {{ $usuario->name }}">
                <i class="ri-save-line me-1" aria-hidden="true"></i> Guardar
              </button>
            </form>
            
            <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="d-inline-block">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que deseas eliminar definitivamente a este usuario?')" aria-label="Eliminar usuario {{ $usuario->name }}">
                <i class="ri-delete-bin-line me-1" aria-hidden="true"></i> Eliminar
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
