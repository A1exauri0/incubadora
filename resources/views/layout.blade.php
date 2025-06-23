<!doctype html>
<html lang="en" class="h-100" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <title>@yield('titulo') - ITTG</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="/docs/5.3/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="favicon.ico" sizes="32x32" type="x-icon">
    <link rel="icon" href="favicon.ico" sizes="16x16" type="x-icon">
    <link rel="composer" href="composer.json">
    <link rel="mask-icon" href="public\tec_logo.svg" color="#712cf9">
    <link rel="icon" href="favicon.ico">
    <meta name="theme-color" content="#712cf9">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">


</head>

<body class="d-flex flex-column h-100">
    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top">
            <div class="container-fluid">
                <a href="/home">
                    <img src="{{ asset('images/logo_tec.png') }}" alt="Logo Tec" width="50"
                        style="margin-right: 10px;">
                </a>
                <a class="navbar-brand" href="/home">IncubaTec</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        @can('mostrar admin')
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/home">Inicio</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Catálogos
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/c_alumnos">Alumnos</a></li>
                                    <li><a class="dropdown-item" href="/c_carreras">Carreras</a></li>
                                    <li><a class="dropdown-item" href="/c_categorias">Categorías</a></li>
                                    <li><a class="dropdown-item" href="/c_tokens">Tokens</a></li>
                                    <li><a class="dropdown-item" href="/c_habilidades">Habilidades</a></li>
                                    <li><a class="dropdown-item" href="/c_mentores">Mentores</a></li>
                                    <li><a class="dropdown-item" href="/c_servicios">Servicios</a></li>
                                    <li><a class="dropdown-item" href="/c_usuarios">Usuarios</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <div class="btn-group dropend">
                                    <a href="/c_proyectos" class="btn btn-primary">Proyectos</a>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
                                        <span class="visually-hidden">Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="/c_participantes">Participantes</a></li>
                                        <li><a class="dropdown-item" href="/c_tipos">Tipos</a></li>
                                        <li><a class="dropdown-item" href="/c_etapas">Etapas</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <div class="btn-group dropend">
                                    <a href="/c_asesores" class="btn btn-primary">Asesores</a>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
                                        <span class="visually-hidden">Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="/c_habilidadesAM_asignar">Asignar
                                                habilidades</a></li>

                                    </ul>
                                </div>
                            </li>
                        @endcan
                        @can('mostrar alumno')
                            {{-- Coloca aquí los elementos de menú para alumnos si los hay --}}
                        @endcan
                        @can('mostrar asesor')
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/home">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/home">Mis Habilidades</a>
                            </li>
                        @endcan
                    </ul>

                    {{-- Mover el menú desplegable del usuario fuera del form si no es estrictamente necesario que esté dentro --}}
                    <ul class="navbar-nav"> {{-- Usamos otro ul para los elementos de la derecha, como el usuario --}}
                        @auth
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form-actual').submit();">
                                        {{-- Cambiado el ID del form --}}
                                        {{ __('Cerrar sesión') }}
                                    </a>
                                    {{-- El formulario oculto de logout debe estar aquí, o fuera de la navbar --}}
                                    <form id="logout-form-actual" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
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

    <!-- Begin page content -->
    <main class="flex-shrink-0">
        <div class="container">
            @yield('content')
        </div>

        <!-- Modal de simbología -->
        <div id="simbologiaEmployeeModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    @yield('modulo_simbologia')
                </div>
            </div>
        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
