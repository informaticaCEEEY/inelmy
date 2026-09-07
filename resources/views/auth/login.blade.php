@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h1 class="h3 fw-bold">INELMY</h1>
                    <p class="text-muted mb-0">
                        Iniciar sesión
                    </p>
                </div>
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Correo electrónico
                        </label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Contraseña
                        </label>

                        <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" required>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" >

                        <label class="form-check-label" for="remember">
                            Recordarme
                        </label>
                    </div>
                    @error('email')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Iniciar sesión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
