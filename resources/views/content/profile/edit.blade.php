@extends('layouts/contentNavbarLayout')

@section('title', 'Ajustes de Perfil')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card mb-4">
      <h5 class="card-header">Configuración del Perfil</h5>

      <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4">
          <div class="avatar avatar-xl bg-primary d-flex align-items-center justify-content-center rounded text-white shadow" style="width:100px; height:100px; font-size: 2.5rem;">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
          </div>
          <div class="button-wrapper">
            <h4 class="mb-1">{{ Auth::user()->name }}</h4>
            <p class="text-muted mb-0">Gestiona la información de tu cuenta de taller</p>
          </div>
        </div>
      </div>

      <hr class="my-0">

      <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
          @csrf
          @method('PUT')
          <div class="row">
            <div class="mb-3 col-md-6">
              <label for="name" class="form-label">Nombre de usuario / Mecánico</label>
              <input class="form-control" type="text" id="name" name="name" value="{{ $user->name }}" autofocus />
            </div>
            <div class="mb-3 col-md-6">
              <label for="email" class="form-label">Email de contacto</label>
              <input class="form-control" type="text" id="email" name="email" value="{{ $user->email }}" />
            </div>
          </div>

          <hr class="my-4">
          <h6 class="mb-3 text-primary"><i class="ri-lock-password-line me-1"></i> Seguridad y Acceso</h6>
          <div class="row">
            <div class="mb-3 col-md-6">
              <label for="password" class="form-label">Nueva Contraseña</label>
              <input class="form-control" type="password" id="password" name="password" placeholder="Dejar en blanco si no deseas cambiarla" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
              <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" placeholder="Repite la nueva contraseña" />
            </div>
          </div>
          <div class="mt-4 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-lg px-5">Guardar Cambios</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection