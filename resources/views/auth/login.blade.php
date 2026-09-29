@extends('layouts.login')
@push('styles')
    @vite('resources/css/login.css')
@endpush
@section('title', 'Iniciar sesión')

@section('content')
<div class="container-fluid p-0 login-page">

    <div class="row g-0 login-container">

        {{-- =====================================================
             PANEL INSTITUCIONAL
        ====================================================== --}}

        <div class="col-md-6 login-brand">
            <div class="brand-content">
                <div class="brand-line"></div>
                <div class="brand-name">INELMY</div>
                <div class="brand-title">Instrumento de Evaluación Lingüística del Maya Yucateco</div>

                <p class="brand-description">
                    Plataforma para la aplicación, seguimiento
                    y consulta de instrumentos de evaluación
                    educativa.
                </p>
            </div>
        </div>

        {{-- =====================================================
             PANEL DE ACCESO
        ====================================================== --}}

        <div class="col-md-6 login-form-panel">
            <div class="login-form-container">
                <div class="login-header">
                    <h1 class="h3">Iniciar sesión</h1>
                    <p>Ingresa tus credenciales para continuar.</p>
                </div>

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    {{-- CORREO --}}
                    <div class="mb-4">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email') }}" autocomplete="email" required autofocus>
                    </div>

                    {{-- CONTRASEÑA --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password"
                            autocomplete="current-password" required>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Recordarme</label>
                    </div>

                    {{-- ERROR --}}
                    @error('email')
                        <div class="alert alert-danger mb-4">
                            {{ $message }}
                        </div>
                    @enderror

                    {{-- BOTÓN --}}
                    <div class="d-grid">
                        <button type="submit" class="btn btn-inelmy">Iniciar sesión</button>
                    </div>

                </form>

                <div class="login-footer">
                    <span>INELMY</span>· Sistema de Evaluación Educativa
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
