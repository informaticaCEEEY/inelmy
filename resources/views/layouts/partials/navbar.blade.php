<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="#">
            INELMY
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('login') }}">
                        Iniciar Sesión
                    </a>
                </li>
                @auth
                    <li class="nav-item">
                        <span class="nav-link">
                            {{ Auth::user()->nombre }}
                        </span>
                    </li>

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">
                                Cerrar sesión
                            </button>
                        </form>
                    </li>
                @endauth

            </ul>

        </div>
    </div>
</nav>
