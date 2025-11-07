<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            BeanQuick
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Enlaces a la izquierda -->
            <ul class="navbar-nav me-auto">
                @auth
                    @if(auth()->user()->rol === 'empresa')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('empresa.dashboard', auth()->user()->id) }}">Dashboard Empresa</a>
                        </li>
                    @elseif(auth()->user()->rol === 'cliente')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cliente.dashboard') }}">Dashboard Cliente</a>
                        </li>
                    @endif
                @endauth
            </ul>

            <!-- Enlaces a la derecha -->
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Registrarse</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Cerrar sesión</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
