<nav class="navbar navbar-expand-lg navbar-inelmy">

    <div class="container-fluid px-3 px-lg-4">

        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/segey100v.png') }}" alt="SEGEY"class="logo-segey">
        </a>

        {{-- Botón responsive --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPrincipal"
            aria-controls="navbarPrincipal" aria-expanded="false" aria-label="Mostrar navegación">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Menu --}}
        <div class="collapse navbar-collapse" id="navbarPrincipal">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @can('dashboard.ver')
                    {{-- Inicio --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            Inicio
                        </a>
                    </li>
                @endcan
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-card-list"></i>
                            Catalogos
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="nav-link {{ request()->routeIs('usuarios.index') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                                    Usuarios
                                </a>
                            </li>
                            <li>
                                <a class="nav-link {{ request()->routeIs('permisos.index') ? 'active' : '' }}" href="{{ route('permisos.index') }}">
                                    Permisos
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                {{-- Aplicaciones --}}
                <li class="nav-item">
                    <a class="nav-link" href="#">Aplicaciones</a>
                </li>
                {{-- Evaluaciones --}}
                <li class="nav-item">
                    <a class="nav-link" href="#">Evaluaciones</a>
                </li>
                {{-- Resultados --}}
                <li class="nav-item">
                    <a class="nav-link" href="#"> Resultados</a>
                </li>
            </ul>

            {{-- Usuario --}}
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ auth()->user()->nombre }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#"> Mi perfil</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
