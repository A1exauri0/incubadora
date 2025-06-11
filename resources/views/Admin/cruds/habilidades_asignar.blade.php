@include('Admin.cruds.layouts.header')
@extends('Admin.cruds.layouts.layout')

@section('titulo', $titulo)
@section('administrar', 'Habilidades de Usuarios')
@section('administrar_s', 'Habilidad')

{{-- Estas secciones no son necesarias para esta vista específica,
    ya que no muestra una tabla CRUD estándar, sino un formulario interactivo.
    Se dejan vacías o comentadas para no interferir con el layout base. --}}
@section('total_registros', '')
@section('columnas')
    {{-- <!-- No hay columnas fijas para esta vista. --> --}}
@endsection
@section('datos')
    {{-- <!-- El contenido de la tabla se gestiona con JS. --> --}}
@endsection
@section('paginacion')
    {{-- <!-- No hay paginación tradicional aquí. --> --}}
@endsection
@section('script_checkboxes')
    {{-- <!-- Los checkboxes no son parte de esta funcionalidad. --> --}}
@endsection
@section('modulo_agregar')
    {{-- <!-- No hay un modal de agregar genérico para esta vista. --> --}}
@endsection
@section('modulo_editar')
    {{-- <!-- No hay un modal de editar genérico para esta vista. --> --}}
@endsection
@section('modulo_eliminar')
    {{-- <!-- No hay un modal de eliminar genérico para esta vista. --> --}}
@endsection
@section('modulo_eliminar_multiple')
    {{-- <!-- No hay un modal de eliminar múltiple genérico para esta vista. --> --}}
@endsection
@section('modulo_ver')
    {{-- <!-- No hay un modal de ver genérico para esta vista. --> --}}
@endsection


{{-- CONTENIDO PRINCIPAL DE LA VISTA --}}
@section('content')

<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-12">
                        <h2>Asignar <b>Habilidades</b> a Asesores y Mentores</h2>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tipo_usuario">Seleccionar Tipo de Usuario:</label>
                        <select id="tipo_usuario" class="form-control">
                            <option value="">-- Seleccione --</option>
                            <option value="asesor">Asesor</option>
                            <option value="mentor">Mentor</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="select_usuario">Seleccionar Asesor/Mentor:</label>
                        <select id="select_usuario" class="form-control" disabled>
                            <option value="">-- Seleccione un tipo de usuario primero --</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h4>Habilidades Actuales:</h4>
                    <ul id="habilidades_actuales_list" class="list-group">
                        <li class="list-group-item text-muted">Seleccione un usuario para ver sus habilidades.</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h4>Agregar Habilidad:</h4>
                    <div class="form-group">
                        <select id="select_habilidad_disponible" class="form-control" disabled>
                            <option value="">-- No hay habilidades disponibles --</option>
                        </select>
                    </div>
                    <button id="btn_add_habilidad" class="btn btn-success" disabled>Agregar Habilidad</button>
                    <div id="add_message" class="mt-2"></div>
                </div>
            </div>

            {{-- Hidden inputs para almacenar el tipo y ID del usuario seleccionado --}}
            <input type="hidden" id="selected_user_type" value="">
            <input type="hidden" id="selected_user_id" value="">

        </div>
    </div>
</div>

@endsection

@section('script_validacion_campos')
    {{-- Si tienes validaciones de JS que quieres reusar, irían aquí. Para esta vista, no hay campos de texto directos. --}}
@endsection

{{-- Script personalizado para esta vista, empujado al stack 'scripts' en el layout --}}
@push('scripts')
<script>
$(document).ready(function() {

    // Token CSRF para las peticiones AJAX (se obtiene del meta tag en el layout)
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Evento al cambiar el tipo de usuario (Asesor/Mentor)
    $('#tipo_usuario').on('change', function() {
        var tipo = $(this).val();
        $('#selected_user_type').val(tipo); // Guardar el tipo seleccionado

        // Limpiar y deshabilitar dropdown de usuario y listas de habilidades
        $('#select_usuario').html('<option value="">-- Cargando... --</option>').prop('disabled', true);
        $('#habilidades_actuales_list').html('<li class="list-group-item text-muted">Seleccione un usuario para ver sus habilidades.</li>');
        $('#select_habilidad_disponible').html('<option value="">-- No hay habilidades disponibles --</option>').prop('disabled', true);
        $('#btn_add_habilidad').prop('disabled', true);
        $('#selected_user_id').val(''); // Resetea el ID del usuario seleccionado
        $('#add_message').empty(); // Limpia mensajes de estado

        if (tipo) {
            $.ajax({
                url: "{{ route('habilidadesAM.getUsuariosPorTipo') }}", // Ruta específica del nuevo CRUD
                method: "POST",
                data: { tipo: tipo },
                success: function(response) {
                    var options = '<option value="">-- Seleccione --</option>';
                    if (response.length > 0) {
                        $.each(response, function(index, usuario) {
                            options += '<option value="' + usuario.id + '">' + usuario.nombre + '</option>';
                        });
                    } else {
                        options += '<option value="">-- No se encontraron usuarios --</option>';
                    }
                    $('#select_usuario').html(options).prop('disabled', false);
                },
                error: function(xhr) {
                    console.error("Error al cargar usuarios:", xhr.responseText);
                    $('#select_usuario').html('<option value="">-- Error al cargar --</option>').prop('disabled', true);
                }
            });
        }
    });

    // Evento al seleccionar un Asesor/Mentor específico
    $('#select_usuario').on('change', function() {
        var idUsuario = $(this).val();
        var tipo = $('#selected_user_type').val();
        $('#selected_user_id').val(idUsuario); // Guardar el ID de usuario seleccionado

        // Limpiar listas y deshabilitar botón de añadir
        $('#habilidades_actuales_list').html('<li class="list-group-item text-muted">Cargando habilidades...</li>');
        $('#select_habilidad_disponible').html('<option value="">-- Cargando habilidades disponibles --</option>').prop('disabled', true);
        $('#btn_add_habilidad').prop('disabled', true);
        $('#add_message').empty();

        if (idUsuario) {
            $.ajax({
                url: "{{ route('habilidadesAM.getHabilidadesUsuario') }}", // Ruta específica del nuevo CRUD
                method: "POST",
                data: { tipo: tipo, idUsuario: idUsuario },
                success: function(response) {
                    displayHabilidades(response.currentSkills, response.availableSkills);
                },
                error: function(xhr) {
                    console.error("Error al cargar habilidades:", xhr.responseText);
                    $('#habilidades_actuales_list').html('<li class="list-group-item text-danger">Error al cargar habilidades.</li>');
                    $('#select_habilidad_disponible').html('<option value="">-- Error al cargar --</option>').prop('disabled', true);
                }
            });
        } else {
            // Si no se selecciona usuario (opción "-- Seleccione --")
            $('#habilidades_actuales_list').html('<li class="list-group-item text-muted">Seleccione un usuario para ver sus habilidades.</li>');
            $('#select_habilidad_disponible').html('<option value="">-- No hay habilidades disponibles --</option>').prop('disabled', true);
            $('#btn_add_habilidad').prop('disabled', true);
        }
    });

    // Función para mostrar las habilidades actuales y llenar el dropdown de disponibles
    function displayHabilidades(currentSkills, availableSkills) {
        var currentListHtml = '';
        if (currentSkills.length > 0) {
            $.each(currentSkills, function(index, habilidad) {
                currentListHtml += '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                                    habilidad.nombre +
                                    '<button type="button" class="btn btn-danger btn-sm remove-habilidad" data-id="' + habilidad.idHabilidad + '">Eliminar</button>' +
                                '</li>';
            });
        } else {
            currentListHtml = '<li class="list-group-item text-muted">Este usuario no tiene habilidades asignadas.</li>';
        }
        $('#habilidades_actuales_list').html(currentListHtml);

        var availableOptionsHtml = '<option value="">-- Seleccione una habilidad --</option>';
        if (availableSkills.length > 0) {
            $.each(availableSkills, function(index, habilidad) {
                availableOptionsHtml += '<option value="' + habilidad.idHabilidad + '">' + habilidad.nombre + '</option>';
            });
            $('#select_habilidad_disponible').html(availableOptionsHtml).prop('disabled', false);
            $('#btn_add_habilidad').prop('disabled', false);
        } else {
            availableOptionsHtml = '<option value="">-- Todas las habilidades están asignadas o no hay habilidades --</option>';
            $('#select_habilidad_disponible').html(availableOptionsHtml).prop('disabled', true);
            $('#btn_add_habilidad').prop('disabled', true);
        }
    }

    // Evento para añadir una habilidad
    $('#btn_add_habilidad').on('click', function() {
        var idHabilidad = $('#select_habilidad_disponible').val();
        var tipo = $('#selected_user_type').val();
        var idUsuario = $('#selected_user_id').val();

        if (idHabilidad && tipo && idUsuario) {
            $.ajax({
                url: "{{ route('habilidadesAM.addHabilidad') }}", // Ruta específica del nuevo CRUD
                method: "POST",
                data: {
                    tipo: tipo,
                    idUsuario: idUsuario,
                    idHabilidad: idHabilidad
                },
                success: function(response) {
                    $('#add_message').html('<div class="alert alert-success">' + response.message + '</div>');
                    // Recargar habilidades después de añadir
                    $('#select_usuario').trigger('change');
                    setTimeout(function(){ $('#add_message').empty(); }, 3000); // Ocultar mensaje
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error al añadir la habilidad.';
                    $('#add_message').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                    setTimeout(function(){ $('#add_message').empty(); }, 5000); // Ocultar mensaje
                    console.error("Error al añadir habilidad:", xhr.responseText);
                }
            });
        } else {
            $('#add_message').html('<div class="alert alert-warning">Por favor, seleccione una habilidad.</div>');
            setTimeout(function(){ $('#add_message').empty(); }, 3000);
        }
    });

    // Evento para eliminar una habilidad (usando delegación de eventos porque los botones se añaden dinámicamente)
    $('#habilidades_actuales_list').on('click', '.remove-habilidad', function() {
        var idHabilidad = $(this).data('id');
        var tipo = $('#selected_user_type').val();
        var idUsuario = $('#selected_user_id').val();

        if (confirm('¿Está seguro de que desea eliminar esta habilidad?')) {
            $.ajax({
                url: "{{ route('habilidadesAM.removeHabilidad') }}", // Ruta específica del nuevo CRUD
                method: "POST",
                data: {
                    tipo: tipo,
                    idUsuario: idUsuario,
                    idHabilidad: idHabilidad
                },
                success: function(response) {
                    // Recargar habilidades después de eliminar
                    $('#select_usuario').trigger('change');
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error al eliminar la habilidad.';
                    alert(errorMessage);
                    console.error("Error al eliminar habilidad:", xhr.responseText);
                }
            });
        }
    });

});
</script>
@endpush