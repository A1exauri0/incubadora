<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Importante para peticiones AJAX --}}
    <!-- Título de la página -->
    <title>@yield('titulo') - ITTG</title>
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo_tec.png') }}">
    <!-- Estilos de Font Awesome, Bootstrap y Google Fonts -->
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

    {{-- Modal de editar perfil --}}
    @include('components.edit-modal')

    {{-- Aquí se renderizarán los scripts pusheados desde otras vistas --}}
    @stack('scripts')

    {{-- Script del modal de editar perfil --}}
    <script>
        $(document).ready(function() {

            const editProfileModal = $('#editProfileModal');
            const roleSpecificFields = $('#roleSpecificFields');
            const editProfileForm = $('#editProfileForm');

            // Escuchar el evento 'show.bs.modal' para cargar los datos cuando el modal se abre
            editProfileModal.on('show.bs.modal', function(event) {
                roleSpecificFields.html(
                    '<p class="text-center text-muted">Cargando datos...</p>'); // Mensaje de carga

                // Realizar una petición AJAX para obtener los datos del usuario
                $.ajax({
                    url: '{{ route('api.user.profile.data') }}',
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Limpiar los campos y construir el formulario dinámicamente
                        roleSpecificFields.empty();

                        const userRole = response.user_role;
                        const profileData = response.profile_data;
                        const userName = response.user_name;
                        const userEmail = response.user_email;

                        let formHtml = '';

                        // Campos comunes (nombre y correo, si no son específicos del rol)
                        formHtml += `
                            <div class="form-group">
                                <label for="name">Nombre de Usuario:</label>
                                <input type="text" class="form-control" id="name" name="name" value="${userName || ''}" readonly>
                                <small class="form-text text-muted">Este es tu nombre de usuario.</small>
                            </div>
                            <div class="form-group">
                                <label for="email">Correo Electrónico:</label>
                                <input type="email" class="form-control" id="email" name="email" value="${userEmail || ''}" readonly>
                                <small class="form-text text-muted">Este es tu correo de registro.</small>
                            </div>
                        `;


                        if (userRole === 'alumno') {
                            formHtml += `
                                <div class="form-group">
                                    <label for="no_control">Número de Control:</label>
                                    <input type="text" class="form-control" id="no_control" name="no_control" value="${profileData ? profileData.no_control : ''}" required>
                                    <div class="invalid-feedback" id="no_control_error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="nombre">Nombre Completo:</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="${profileData ? profileData.nombre : ''}" required>
                                    <div class="invalid-feedback" id="nombre_error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="carrera">Carrera:</label>
                                    <select class="form-control" id="carrera" name="carrera" required>
                                        <option value="">Selecciona una carrera</option>
                            `;
                            response.carreras.forEach(carrera => {
                                const selected = profileData && profileData.carrera ===
                                    carrera ? 'selected' : '';
                                formHtml +=
                                    `<option value="${carrera}" ${selected}>${carrera}</option>`;
                            });
                            formHtml += `
                                    </select>
                                    <div class="invalid-feedback" id="carrera_error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="telefono">Teléfono:</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="${profileData ? profileData.telefono : ''}" required>
                                    <div class="invalid-feedback" id="telefono_error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="semestre">Semestre:</label>
                                    <input type="number" class="form-control" id="semestre" name="semestre" value="${profileData ? profileData.semestre : ''}" min="1" max="10" required>
                                    <div class="invalid-feedback" id="semestre_error"></div>
                                </div>
                            `;
                        } else if (userRole === 'asesor' || userRole === 'mentor') {
                            formHtml += `
                                <div class="form-group">
                                    <label for="nombre">Nombre Completo:</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="${profileData ? profileData.nombre : ''}" required>
                                    <div class="invalid-feedback" id="nombre_error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="telefono">Teléfono:</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="${profileData ? profileData.telefono : ''}" required>
                                    <div class="invalid-feedback" id="telefono_error"></div>
                                </div>
                            `;
                        } else {
                            formHtml =
                                '<p class="text-center text-muted">No se requieren datos adicionales para tu rol.</p>';
                        }

                        roleSpecificFields.html(formHtml);
                    },
                    error: function(xhr) {
                        console.error('Error al cargar los datos del perfil:', xhr
                            .responseText);
                        roleSpecificFields.html(
                            '<p class="text-center text-danger">Error al cargar los datos. Inténtalo de nuevo.</p>'
                        );
                    }
                });
            });

            // Escuchar el evento 'submit' del formulario del modal
            editProfileForm.on('submit', function(e) {
                e.preventDefault(); // Prevenir el envío normal del formulario

                // Limpiar mensajes de error anteriores
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                const formData = $(this).serialize(); // Obtener los datos del formulario

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            editProfileModal.modal('hide'); // Cerrar el modal
                            alert(response.message); // Usar alert de JS
                            location.reload(); // Recargar la página para reflejar los cambios
                        } else {
                            alert('Error: ' + (response.message ||
                                'Ocurrió un error desconocido.'));
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // Errores de validación
                            const errors = xhr.responseJSON.errors;
                            for (const field in errors) {
                                const input = $(`#${field}`);
                                input.addClass('is-invalid');
                                $(`#${field}_error`).text(errors[field][0]).show();
                            }
                        } else {
                            alert('Error al guardar los datos: ' + (xhr.responseJSON.message ||
                                xhr.statusText));
                        }
                    }
                });
            });
        });
    </script>
</body>
@include('components.footer')

</html>
