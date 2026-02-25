@extends('layouts/blankLayout')

@section('title', 'Login Basic - Pages')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-6 mx-4">
            <!-- Login -->
            <div class="card p-sm-7 p-2">
                <!-- Logo -->
                <div class="app-brand justify-content-center mt-5">
                    <a href="{{ url('/') }}" class="app-brand-link gap-3">
                        <span class="app-brand-logo demo">@include('_partials.macros')</span>
                        <span class="app-brand-text demo text-heading fw-semibold">Gestor</span>
                    </a>
                </div>
                <!-- /Logo -->

                <div class="card-body mt-1">
                    <h4 class="mb-1">Bienvenido</h4>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="admin@taller.com" autofocus>
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">Contraseña</label>
                            <input type="password" id="password" class="form-control" name="password" placeholder="············" />
                        </div>
                        <button class="btn btn-primary d-grid w-100" type="submit">Entrar</button>
                    </form>
                </div>
            </div>
            <!-- register -->
            <div class="text-center mt-3">
                <p class="mb-0">
                    <span>¿No tienes una cuenta?</span>
                    <a href="{{route('register')}}">
                        <span>Regístrate aquí</span>
                    </a>
                </p>
            </div>


            <!-- /Login 
            <img src="{{ asset('assets/img/illustrations/tree-3.png') }}" alt="auth-tree" class="authentication-image-object-left d-none d-lg-block" />
            <img src="{{ asset('assets/img/illustrations/auth-basic-mask-light.png') }}" class="authentication-image d-none d-lg-block scaleX-n1-rtl" height="172" alt="triangle-bg" />
            <img src="{{ asset('assets/img/illustrations/tree.png') }}" alt="auth-tree" class="authentication-image-object-right d-none d-lg-block" />-->
        </div>
    </div>
</div>
@endsection