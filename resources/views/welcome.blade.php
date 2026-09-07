@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

    <div class="container-fluid">

        <div class="mb-4">
            <h1 class="fw-bold">Bienvenido a INELMY</h1>
            <p class="text-muted">
                Sistema de gestión y evaluación.
            </p>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    Bootstrap 5 funcionando correctamente
                </h5>

                <p class="card-text">
                    Esta será la estructura base de nuestra aplicación.
                </p>

                <button class="btn btn-primary">
                    Prueba
                </button>
            </div>
        </div>

    </div>

@endsection
