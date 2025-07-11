<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Título de la página -->
    <title>@yield('titulo') - ITTG</title>
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo_tec.png') }}">
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Estilos de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap 4.5.0 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css"
        xintegrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="d-flex flex-column min-vh-100" style="padding-top: 20px;">
    {{-- Header --}}
    @include('components.header')

    <main role="main" class="flex-shrink-0">
        <div class="container">
            @yield('content')
        </div>

        <!-- Modal de Simbología -->
        <div id="simbologiaModal" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="simbologiaModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    @yield('modulo_simbologia')
                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    @include('components.footer')

    <!-- jQuery (Versión completa) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        xintegrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>

    <!-- Popper.js (requerido por Bootstrap 4) -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        xintegrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>

    <!-- JS de Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"
        xintegrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous">
    </script>

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
                console.log("Evento 'show.bs.modal' disparado. Intentando cargar datos...");
                roleSpecificFields.html('<p class="text-center text-muted">Cargando datos...</p>'); // Mensaje de carga

                // Realizar una petición AJAX para obtener los datos del usuario
                $.ajax({
                    url: '{{ route('api.user.profile.data') }}',
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log("Datos de perfil cargados exitosamente:", response);
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
                                const selected = profileData && profileData.carrera === carrera ? 'selected' : '';
                                formHtml += `<option value="${carrera}" ${selected}>${carrera}</option>`;
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
                            formHtml = '<p class="text-center text-muted">No se requieren datos adicionales para tu rol.</p>';
                        }

                        roleSpecificFields.html(formHtml);
                    },
                    error: function(xhr) {
                        console.error('Error al cargar los datos del perfil:', xhr.responseText);
                        roleSpecificFields.html('<p class="text-center text-danger">Error al cargar los datos. Inténtalo de nuevo.</p>');
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
                            alert('Error: ' + (response.message || 'Ocurrió un error desconocido.'));
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
                            alert('Error al guardar los datos: ' + (xhr.responseJSON.message || xhr.statusText));
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
