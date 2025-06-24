<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top">
        <div class="container-fluid">
            <a href="/home">
                <img src="{{ asset('images/logo_tec.png') }}" alt="Logo Tec" width="50" style="margin-right: 10px;">
            </a>
            <a class="navbar-brand font-weight-bold" href="/home">IncubaTec</a>

            <!-- Collapse button con Bootstrap 4 -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ml-5">
                    <li class="nav-item">
                        <a class="nav-link active" href="/home">Inicio</a>
                    </li>

                    <!-- Dropdown Catálogos -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle font-weight-bold" href="#" id="catalogosDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Catálogos
                        </a>
                        <div class="dropdown-menu" aria-labelledby="catalogosDropdown">
                            <a class="dropdown-item" href="/c_alumnos">Alumnos</a>
                            <a class="dropdown-item" href="/c_carreras">Carreras</a>
                            <a class="dropdown-item" href="/c_categorias">Categorías</a>
                            <a class="dropdown-item" href="/c_tokens">Tokens</a>
                            <a class="dropdown-item" href="/c_habilidades">Habilidades</a>
                            <a class="dropdown-item" href="/c_habilidadesAM">Habilidades AM</a>
                            <a class="dropdown-item" href="/c_mentores">Mentores</a>
                            <a class="dropdown-item" href="/c_servicios">Servicios</a>
                        </div>
                    </li>

                    <!-- Dropdown Proyectos -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle font-weight-bold" href="#" id="proyectosDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Proyectos
                        </a>
                        <div class="dropdown-menu" aria-labelledby="proyectosDropdown">
                            <a class="dropdown-item" href="/c_proyectos">Todos</a>
                            <a class="dropdown-item" href="/c_participantes">Participantes</a>
                            <a class="dropdown-item" href="/c_tipos">Tipos</a>
                            <a class="dropdown-item" href="/c_etapas">Etapas</a>
                        </div>
                    </li>

                    <!-- Dropdown Asesores -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle font-weight-bold" href="#" id="asesoresDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Asesores
                        </a>
                        <div class="dropdown-menu" aria-labelledby="asesoresDropdown">
                            <a class="dropdown-item" href="/c_asesores">Listado</a>
                            <a class="dropdown-item" href="/c_habilidadesAM_asignar">Asignar habilidades</a>
                        </div>
                    </li>
                </ul>

                <!-- Logout -->
                <ul class="navbar-nav ml-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Cerrar sesión
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</header>
