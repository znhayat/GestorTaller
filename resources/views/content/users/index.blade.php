@extends('layouts/contentNavbarLayout')

@section('title', 'Gestión de Usuarios')

@section('content')
  <style>
    .search-input-width {
      width: 300px;
    }

    .user-row {
      cursor: pointer;
      transition: background 0.2s;
    }

    .user-row:hover {
      background-color: rgba(105, 108, 255, 0.05) !important;
    }

    @media (max-width: 768px) {
      .search-input-width {
        width: 100% !important;
      }
    }
  </style>

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h5 class="mb-0">Administración de Usuarios</h5>
        <p class="text-muted mb-0 small">Haz clic en un trabajador para editar sus datos o contraseña.</p>
      </div>
      @if ($pendientes > 0)
        <div class="alert alert-warning mb-0 py-2 px-3 border-warning d-flex align-items-center shadow-sm" role="alert">
          <i class="ri-error-warning-line me-2 fs-4"></i>
          <div>
            Hay <strong>{{ $pendientes }}</strong> usuario(s) pendiente(s) de aprobación.
          </div>
        </div>
      @endif
      <div class="input-group input-group-merge search-input-width">
        <span class="input-group-text"><i class="ri-search-line text-primary"></i></span>
        <input type="text" id="buscar-trabajador" class="form-control" placeholder="Buscar trabajador...">
      </div>
    </div>

    @if (session('success'))
      <div class="alert alert-success mx-4">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger mx-4">{{ session('error') }}</div>
    @endif

    <div class="table-responsive text-nowrap">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Trabajador</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Registro</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        @foreach ($usuarios as $usuario)
          @php
            $bgColors = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-dark'];
            $color = $bgColors[$usuario->id % count($bgColors)];
          @endphp
          <tr class="user-row">
            <td onclick="abrirModalUsuario({{ json_encode($usuario) }})">
              <div class="d-flex align-items-center">
                <div
                  class="avatar avatar-sm {{ $color }} me-3 d-flex align-items-center justify-content-center rounded-circle">
                  <span class="text-white fw-bold">{{ strtoupper(substr($usuario->name, 0, 1)) }}</span>
                </div>
                <div>
                  <h6 class="mb-0 fw-bold text-dark">{{ $usuario->name }}</h6>
                  <small class="text-muted">{{ $usuario->email }}</small>
                </div>
              </div>
            </td>
            <td>
              <span class="badge bg-label-{{ $usuario->role === 'admin' ? 'danger' : 'primary' }}">
                {{ strtoupper($usuario->role) }}
              </span>
            </td>
            <td>
              <span class="badge rounded-pill bg-{{ $usuario->is_approved ? 'success' : 'secondary' }}">
                {{ $usuario->is_approved ? 'Aprobado' : 'Bloqueado' }}
              </span>
            </td>
            <td><small class="text-muted">{{ $usuario->created_at->format('d/m/Y') }}</small></td>
            <td class="text-center" onclick="event.stopPropagation()">
              <div class="d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-sm btn-primary"
                  onclick="abrirModalUsuario({{ json_encode($usuario) }})">
                  <i class="ri-edit-2-line me-1"></i> Editar
                </button>
                <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="d-inline-block">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('¿Seguro que deseas eliminar definitivamente a este usuario?')">
                    <i class="ri-delete-bin-line me-1"></i> Eliminar
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- MODAL DE EDICIÓN DE USUARIO -->
  <div class="modal fade" id="modalUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-bottom">
          <h5 class="modal-title fw-bold" id="modalUsuarioLabel">Editar Trabajador</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formUsuario" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-12">
                <label class="form-label fw-bold">Nombre Completo</label>
                <input type="text" name="name" id="edit_name" class="form-control" readonly>
                <small class="text-muted">El nombre no se puede cambiar por seguridad.</small>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-bold">Rol de Acceso</label>
                <select name="role" id="edit_role" class="form-select">
                  <option value="user">USER (Operario)</option>
                  <option value="admin">ADMIN (Gestor)</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-bold">Estado de Cuenta</label>
                <select name="is_approved" id="edit_is_approved" class="form-select">
                  <option value="1">Aprobado / Activo</option>
                  <option value="0">Bloqueado / Inactivo</option>
                </select>
              </div>

              <div class="col-md-12 mt-4">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                  <i class="ri-lock-password-line me-2 fs-4"></i>
                  <div>
                    Deje la contraseña en blanco si no desea cambiarla.
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold text-danger">Nueva Contraseña</label>
                  <div class="input-group input-group-merge">
                    <input type="password" name="password" id="edit_password" class="form-control"
                      placeholder="Mínimo 8 caracteres (letras, números y símbolos)">
                    <span class="input-group-text cursor-pointer"
                      onclick="togglePassword('edit_password', 'toggleIcon1')"><i class="ri-eye-line"
                        id="toggleIcon1"></i></span>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold">Confirmar Nueva Contraseña</label>
                  <div class="input-group input-group-merge">
                    <input type="password" name="password_confirmation" id="edit_password_confirmation"
                      class="form-control" placeholder="Repite la contraseña">
                    <span class="input-group-text cursor-pointer"
                      onclick="togglePassword('edit_password_confirmation', 'toggleIcon2')"><i class="ri-eye-line"
                        id="toggleIcon2"></i></span>
                  </div>
                  <div id="password-match-msg" class="form-text mt-1 d-none text-danger">Las contraseñas no coinciden.
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer border-top">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

@section('page-script')
  <script>
    function abrirModalUsuario(usuario) {
      const form = document.getElementById('formUsuario');
      form.action = `/usuarios/${usuario.id}`;

      document.getElementById('edit_name').value = usuario.name;
      document.getElementById('edit_role').value = usuario.role;
      document.getElementById('edit_is_approved').value = usuario.is_approved ? "1" : "0";
      document.getElementById('edit_password').value = "";
      document.getElementById('edit_password_confirmation').value = "";
      document.getElementById('password-match-msg').classList.add('d-none');

      document.getElementById('modalUsuarioLabel').innerText = `Editar: ${usuario.name}`;

      const modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
      modal.show();
    }

    function togglePassword(inputId, iconId) {
      const passInput = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      if (passInput.type === "password") {
        passInput.type = "text";
        icon.classList.replace('ri-eye-line', 'ri-eye-off-line');
      } else {
        passInput.type = "password";
        icon.classList.replace('ri-eye-off-line', 'ri-eye-line');
      }
    }

    document.getElementById('formUsuario').addEventListener('submit', function(e) {
      const p1 = document.getElementById('edit_password').value;
      const p2 = document.getElementById('edit_password_confirmation').value;
      const msg = document.getElementById('password-match-msg');

      if (p1 !== "" && p1 !== p2) {
        e.preventDefault();
        msg.classList.remove('d-none');
        return false;
      }
    });

    document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.getElementById('buscar-trabajador');
      const tableRows = document.querySelectorAll('.user-row');

      if (searchInput) {
        searchInput.addEventListener('input', function() {
          const query = this.value.toLowerCase();
          tableRows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
          });
        });
      }
    });
  </script>
@endsection
