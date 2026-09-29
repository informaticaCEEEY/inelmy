<nav class="navbar navbar-expand-lg navbar-inelmy">

    <div class="container-fluid">

        {{-- Marca --}}
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            INELMY
        </a>

        {{-- Botón responsive --}}
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarPrincipal"
            aria-controls="navbarPrincipal"
            aria-expanded="false"
            aria-label="Mostrar navegación">

            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navegación --}}
        <div class="collapse navbar-collapse" id="navbarPrincipal">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                {{-- Inicio --}}
                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        Inicio
                    </a>
                </li>

                {{-- Aplicaciones --}}
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Aplicaciones
                    </a>
                </li>

                {{-- Evaluaciones --}}
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Evaluaciones
                    </a>
                </li>

                {{-- Resultados --}}
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Resultados
                    </a>
                </li>

            </ul>

            {{-- Usuario --}}
            <ul class="navbar-nav">

                <li class="nav-item dropdown">

                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                        {{ auth()->user()->nombre }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <a class="dropdown-item" href="#">
                                Mi perfil
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button
                                    type="submit"
                                    class="dropdown-item">
                                    Cerrar sesión
                                </button>
                            </form>
                        </li>

                    </ul>

                </li>

            </ul>

        </div>

    </div>

</nav>
