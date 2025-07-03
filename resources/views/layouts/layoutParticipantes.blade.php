<!DOCTYPE html>
<html lang="es"> {{-- Cambiado de 'en' a 'es' --}}

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Importante para peticiones AJAX --}}
    <title>@yield('titulo') - ITTG</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> {{-- Mantener esta versión --}}
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/participantes.css') }}">
    @yield('script_checkboxes')
</head>

<body class="d-flex flex-column min-vh-100" style="padding-top: 70px;">
    @include('components.header')
    <div class="container-xl">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-6">
                            <h2>Administrar <b>@yield('administrar')</b></h2>
                        </div>
                        <div class="col-sm-6 d-flex justify-content-end">
                            {{-- layoutParticipantes.blade.php --}}

                            <form action="{{ route('participantes.generarPDF') }}" method="POST">
                                @csrf
                                <input type="hidden" name="clave_proyecto" value="{{ $clave_proyecto }}">
                                <button type="submit" class="btn btn-danger d-flex align-items-center"
                                    style="font-size: 14px; padding: 6px 12px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        fill="currentColor" class="bi bi-filetype-pdf mr-2" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z" />
                                    </svg>
                                    <span>PDF</span>
                                </button>
                            </form>

                            <a id="btnVerEstadisticas" href="#stadisticsEmployeeModal" class="btn btn-primary"
                                data-toggle="modal">
                                <i class="material-icons">&#xE88E;</i> <span>Ver estadísticas</span>
                            </a>
                            <a id="btnAgregar" href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
                                <i class="material-icons">&#xE147;</i> <span>Agregar @yield('administrar_s')</span>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- Dropdown para seleccionar proyectos -->
                <div class="dropdown-container">
                    <label for="proyectoDropdown">Selecciona un proyecto:</label>
                    <select id="proyectoDropdown" class="form-control proyecto-dropdown">
                        <option value="" id="vacio">-- Seleccionar --</option>
                        @foreach ($proyectos as $proyecto)
                            <option value="{{ $proyecto->clave_proyecto }}"
                                {{ request('clave_proyecto') == $proyecto->clave_proyecto ? 'selected' : '' }}>
                                {{ $proyecto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <script>
                    // Mostrar la tabla si ya hay un proyecto seleccionado al cargar la página
                    window.onload = function() {
                        var claveProyecto = document.getElementById('proyectoDropdown').value;
                        if (claveProyecto) {
                            document.getElementById('tablaWrapper').style.display = 'block';
                        }
                    };

                    document.getElementById('proyectoDropdown').addEventListener('change', function() {
                        var claveProyecto = this.value;

                        if (claveProyecto) {
                            // Redirigir a la página de participantes con la clave del proyecto
                            window.location.href = "/c_participantes" + "?clave_proyecto=" + claveProyecto;
                        } else {
                            // Ocultar la tabla si no se selecciona ningún proyecto
                            document.getElementById('tablaWrapper').style.display = 'none';
                        }
                    });
                </script>


                <div id="tablaWrapper" style="display: none;">
                    <table id="tabla_datos" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Líder</th>

                                <th>
                                    Alumnos
                                </th>
                                <th>
                                    Carrera
                                </th>
                                <th>
                                    Asesores
                                </th>
                                <th>
                                    Mentores
                                </th>
                                @yield('columnas')
                            </tr>
                        </thead>
                        <tbody>
                            @yield('datos')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal HTML -->
    <div id="addEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                @yield('modulo_agregar')
            </div>
        </div>
    </div>

    <!-- Delete Modal HTML -->
    <div id="deleteEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                @yield('modulo_eliminar')
            </div>
        </div>
    </div>

    <!-- Statistics Modal HTML -->
    <div id="stadisticsEmployeeModal" class="modal fade">
        <div class="modal-dialog" style="max-width: 90%; width: auto;"> <!-- Aplicando estilo directo aquí -->
            <div class="modal-content">
                @yield('modulo_estadisticas')
            </div>
        </div>
    </div>

    {{-- Incluir el nuevo modal de cambio de líder --}}
    @yield('modulo_cambiar_lider')

    @stack('scripts')

</body>
@include('components.footer')

</html>
