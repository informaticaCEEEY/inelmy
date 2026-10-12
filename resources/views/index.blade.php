@extends('layouts.app')

@push('styles')
    @vite('resources/css/navbar.css')
    @vite('resources/css/dashboard.css')
@endpush

@section('title', 'INELMY')

@section('content')

<div class="dashboard-page">

    <div class="container-fluid px-4 py-4">

        {{-- Encabezado --}}
        <div class="dashboard-header mb-4">
            <h1>Bienvenido a INELMY</h1>

            <p>
                Sistema de gestión y evaluación.
            </p>
        </div>


        {{-- Tarjetas principales --}}
        <div class="row g-4 mb-4">

            {{-- Aplicaciones --}}
            <div class="col-12 col-sm-6 col-xl-3">

                <div class="dashboard-card h-100">

                    <div class="dashboard-card-icon">
                        <i class="bi bi-grid"></i>
                    </div>

                    <div>
                        <h5>Aplicaciones</h5>

                        <p>
                            Consulta y administra las aplicaciones
                            disponibles.
                        </p>

                        <a href="#" class="dashboard-card-link">
                            Ver aplicaciones
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                </div>

            </div>


            {{-- Evaluaciones --}}
            <div class="col-12 col-sm-6 col-xl-3">

                <div class="dashboard-card h-100">

                    <div class="dashboard-card-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>

                    <div>
                        <h5>Evaluaciones</h5>

                        <p>
                            Accede a los instrumentos de evaluación.
                        </p>

                        <a href="#" class="dashboard-card-link">
                            Ver evaluaciones
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                </div>

            </div>


            {{-- Resultados --}}
            <div class="col-12 col-sm-6 col-xl-3">

                <div class="dashboard-card h-100">

                    <div class="dashboard-card-icon">
                        <i class="bi bi-bar-chart"></i>
                    </div>

                    <div>
                        <h5>Resultados</h5>

                        <p>
                            Consulta los resultados de las evaluaciones.
                        </p>

                        <a href="#" class="dashboard-card-link">
                            Ver resultados
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                </div>

            </div>


            {{-- Administración --}}
            <div class="col-12 col-sm-6 col-xl-3">

                <div class="dashboard-card h-100">

                    <div class="dashboard-card-icon">
                        <i class="bi bi-gear"></i>
                    </div>

                    <div>
                        <h5>Administración</h5>

                        <p>
                            Gestiona usuarios y configuración del sistema.
                        </p>

                        <a href="#" class="dashboard-card-link">
                            Administrar
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                </div>

            </div>

        </div>


        {{-- Área informativa --}}
        <div class="row g-4">

            <div class="col-12 col-lg-8">

                <div class="dashboard-panel h-100">

                    <div class="dashboard-panel-header">
                        <div>
                            <h5>Actividad reciente</h5>
                            <p>Resumen de actividad del sistema.</p>
                        </div>
                    </div>

                    <div class="dashboard-empty">

                        <i class="bi bi-info-circle"></i>

                        <p>
                            Aquí se mostrarán las actividades
                            recientes de INELMY.
                        </p>

                    </div>

                </div>

            </div>


            <div class="col-12 col-lg-4">

                <div class="dashboard-panel h-100">

                    <div class="dashboard-panel-header">
                        <div>
                            <h5>Sesión actual</h5>
                            <p>Información del usuario.</p>
                        </div>
                    </div>

                    <div class="user-summary">

                        <div class="user-avatar">
                            <i class="bi bi-person"></i>
                        </div>

                        <div>
                            <strong>
                                {{ auth()->user()->nombre }}
                            </strong>

                            <span>
                                {{ auth()->user()->email }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection
