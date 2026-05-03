@extends('layouts/blankLayout')

@section('title', 'Registro de Personal')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-6 mx-4">
            <!-- Register Card -->
            <div class="card p-sm-7 p-2">
                <!-- Logo -->
                <div class="app-brand justify-content-center mt-5">
                    <a href="{{ url('/') }}" class="app-brand-link gap-3">
                        <span class="app-brand-logo demo">@include('_partials.macros', ['width' => '150', 'height' => '150'])</span>
                    </a>
                </div>
                <!-- /Logo -->
                <div class="card-body mt-1">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form id="formAuthentication" class="mb-5" action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="form-floating form-floating-outline mb-5">
                            <input type="text" class="form-control" id="username" name="name" placeholder="Tu nombre" value="{{ old('name') }}" autofocus required />
                            <label for="username">Nombre Completo</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-5">
                            <input type="email" class="form-control" id="email" name="email" placeholder="admin@taller.com" value="{{ old('email') }}" required />
                            <label for="email">Email</label>
                        </div>
                        <div class="mb-5 form-password-toggle">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="Mínimo 8 caracteres (letras, números y símbolos)" required />
                                    <label for="password">Contraseña</label>
                                </div>
                                <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                            </div>
                        </div>

                        <div class="mb-5 form-password-toggle">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="············" required />
                                    <label for="password_confirmation">Confirmar Contraseña</label>
                                </div>
                                <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                            </div>
                        </div>

                        <button class="btn btn-primary d-grid w-100 mb-5" type="submit">Registrarse</button>
                    </form>
                    <p class="text-center mb-5">
                        <span>¿Ya tienes una cuenta?</span>
                        <a href="{{ url('/login') }}">
                            <span>Inicia sesión</span>
                        </a>
                    </p>
                </div>
            </div>
            <!-- Register Card 
            <img src="{{ asset('assets/img/illustrations/tree-3.png') }}" alt="auth-tree" class="authentication-image-object-left d-none d-lg-block" />
            <img src="{{ asset('assets/img/illustrations/auth-basic-mask-light.png') }}" class="authentication-image d-none d-lg-block scaleX-n1-rtl" height="172" alt="triangle-bg" />
            <img src="{{ asset('assets/img/illustrations/tree.png') }}" alt="auth-tree" class="authentication-image-object-right d-none d-lg-block" />-->
        </div>
    </div>
</div>
@endsection