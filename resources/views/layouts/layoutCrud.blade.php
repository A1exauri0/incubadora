<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Importante para peticiones AJAX --}}
    <!-- Título de la página -->
    <title>@yield('titulo') - ITTG</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styleCrud.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}">
    @yield('script_checkboxes')
</head>

<body class="d-flex flex-column min-vh-100" style="padding-top: 70px;">
    @include('components.header')
    @section('content')
        <div class="container-xl"> 
            <div class="table-responsive">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h2>Administrar <b>@yield('administrar')</b></h2>
                            </div>
                            <div class="col-sm-6">
                                @hasSection('modulo_agregar')
                                    <a id="btnAgregar" href="#addEmployeeModal" class="btn btn-success"
                                        data-toggle="modal"><i class="material-icons"></i> <span>Agregar
                                            @yield('administrar_s')</span></a>
                                @endif
                                {{-- Estos botones solo aparecen si la sección 'modulo_eliminar_multiple' existe --}}
                                @hasSection('modulo_eliminar_multiple')
                                    <a id="btnEliminar" href="#deleteMultipleEmployeeModal" class="btn btn-danger"
                                        data-toggle="modal">
                                        <i class="material-icons"></i> <span>Eliminar</span></a>
                                @endif

                            </div>
                        </div>
                    </div>
                    <table id='tabla_datos' class="table table-striped table-hover">
                        <thead>
                            <tr>
                                @hasSection('script_checkboxes')
                                    <th>
                                        <span class="custom-checkbox">
                                            <input type="checkbox" id="selectAll">
                                            <label for="selectAll"></label>
                                        </span>
                                    </th>
                                @endif
                                @yield('columnas')
                            </tr>
                        </thead>
                        <tbody>
                            @yield('datos')
                        </tbody>
                    </table>
                    <div class="clearfix">
                        {{-- <div class="hint-text ocultar">Mostrando <b>@yield('mostrando')</b> de <b>@yield('total_registros')</b> registros</div> --}}
                        <div class="hint-text">Mostrando un máximo <b>20</b> registros por página, hay un total de
                            <b>@yield('total_registros')</b> registros encontrados.
                        </div>
                        @yield('paginacion')
                    </div>
                </div>
            </div>
        </div>
        <div id="addEmployeeModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    @yield('modulo_agregar')
                </div>
            </div>
        </div>
        <div id="editEmployeeModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    @yield('modulo_editar')
                </div>
            </div>
        </div>
        <div id="deleteEmployeeModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    @yield('modulo_eliminar')
                </div>
            </div>
        </div>
        <div id="deleteMultipleEmployeeModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    @yield('modulo_eliminar_multiple')
                </div>
            </div>
        </div>

        {{-- View Modal HTML (para ver detalles) --}}
        <div id="viewProjectModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    @yield('modulo_ver')
                </div>
            </div>
        </div>
    @show


    @stack('scripts')

</body>
@include('components.footer')

</html>
