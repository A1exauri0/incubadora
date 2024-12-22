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


    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, 0.1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }

        .bd-mode-toggle .dropdown-menu .active .bi {
            display: block !important;
        }

        /* Propio */
        .dropdown a {
            display: block;
            color: #ffffff;
            text-decoration: none;
            padding: 8px 15px;
        }

        .dropdown .content {
            display: none;
            position: absolute;
            background-color: #036ee0;
            min-width: 100px;
            box-shadow: 2px 2px 2px #000000;
        }

        .dropdown .content a {
            text-decoration: none;
            color: #ffffff;
        }

        .nav-item .dropdown:hover {
            background-color: #084b92;
            cursor: pointer;
        }

        .dropdown .content a:hover {
            background-color: #084b92;
            cursor: pointer;
        }

        .dropdown:hover .content {
            display: block;
        }

        .dropdown:hover .nav-link {
            background-color: #084b92;
        }

        .dropdown.position-fixed {
            display: none;
        }

        .navbar {
            background-color: #036ee0;
        }

        .navbar-brand {
            margin-bottom: 2px;
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">
    <header>
        <!-- Fixed navbar -->
        <nav class="navbar navbar-expand-md navbar-dark fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Incubadora</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/home">Inicio</a>
                        </li>
                        <li class="nav-item dropdown">
                            <!--DROPDOWN-->
                            <div class="dropdown">
                                <a class="nav-link active" href="#">Catálogos</a>
                                <div class="content">
                                    <a href="/c_alumnos">Alumnos</a>
                                    <a href="/c_asesores">Asesores</a>
                                    <a href="/c_carreras">Carreras</a>
                                    <a href="/c_categorias">Categorías</a>
                                    <a href="/c_etapas">Etapas</a>
                                    <a href="/c_habilidades">Habilidades</a>
                                    <a href="/c_mentores">Mentores</a>
                                    <a href="/c_participantes">Participantes</a>
                                    <a href="/c_proyectos">Proyectos</a>
                                    <a href="/c_servicios">Servicios</a>
                                    <a href="/c_tipos">Tipos</a>
                                </div>
                            </div>
                            <!--DROPDOWN-->
                        </li>
                        </ul>
                    <form class="d-flex" role="search" id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-danger" type="submit"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Cerrar sesión
                        </button>
                    </form>
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

    <footer class="footer mt-auto py-3 bg-body-tertiary">
        <div class="container">
            <span class="text-body-secondary" style="font-size: 13px;">
                Tecnológico Nacional de México, Campus Tuxtla Gutiérrez <br>
                Incubadora <br>
                Todos los derechos reservados &copy; 2024
            </span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>