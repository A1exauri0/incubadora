{{-- INICIO DEL CÓDIGO PARA: proyectos.blade.php --}}
@include('Admin.cruds.layouts.header')

<!-- Se extiende de la clase layout en CRUDs (solamente para CRUDs) -->
@extends('Admin.cruds.layouts.layout')

<!-- Se recibe la variable del título que tendrá la página en la pestaña -->
@section('titulo', $titulo)

<!-- ¿Qué se va a administrar en plural? -->
@section('administrar', 'Proyectos')

<!-- ¿Qué se va a administrar en singular? -->
@section('administrar_s', 'Proyecto')

@section('total_registros', $total_registros)

{{-- Script para checkboxes (se integra con el modulo para eliminar multiples registros) --}}
@section('script_checkboxes')
    <script>
        $(document).ready(function() {
            // Arreglo para almacenar los números de control seleccionados
            var numerosControlSeleccionados = [];

            // Función para actualizar el arreglo cuando se selecciona o deselecciona un checkbox
            function actualizarNumerosControlSeleccionados() {
                numerosControlSeleccionados = [];
                $('table tbody input[type="checkbox"]:checked').each(function() {
                    numerosControlSeleccionados.push($(this).val());

                    if (!document.getElementById('clave_proyecto_eliminar_' + $(this).val())) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'clave_proyecto_eliminar_' + $(this).val();
                        input.id = 'clave_proyecto_eliminar_' + $(this).val();
                        input.value = $(this).val();
                        document.getElementById('formulario_eliminar_multiple').appendChild(input);
                    }
                });

                $('table tbody input[type="checkbox"]:not(:checked)').each(function() {
                    if (document.getElementById('clave_proyecto_eliminar_' + $(this).val())) {
                        document.getElementById('clave_proyecto_eliminar_' + $(this).val()).remove();
                    }
                });

                boton_eliminar = document.getElementById('btnEliminar');

                if (numerosControlSeleccionados.length === 0) { //Si está vacío el botón se inhabilita
                    boton_eliminar.classList.add('boton-deshabilitado');
                } else { //Si tiene al menos un elemento, el botón se habilita.
                    boton_eliminar.classList.remove('boton-deshabilitado');
                }
            }

            $('[data-toggle="tooltip"]').tooltip();

            var checkbox = $('table tbody input[type="checkbox"]');
            $("#selectAll").click(function() {
                if (this.checked) {
                    checkbox.each(function() {
                        this.checked = true;
                    });
                } else {
                    checkbox.each(function() {
                        this.checked = false;
                    });
                }
                actualizarNumerosControlSeleccionados();
            });
            checkbox.click(function() {
                if (!this.checked) {
                    $("#selectAll").prop("checked", false);
                }
                actualizarNumerosControlSeleccionados();
            });

            actualizarNumerosControlSeleccionados();
        });
    </script>
@endsection

{{-- Script para validación de campos --}}
@section('script_validacion_campos')
    <script>
        // ... (Tu script de validación de campos queda igual, no es necesario pegarlo de nuevo) ...
    </script>
@endsection

<!-- Se recibe la lista de columnas a mostrar (nombres) -->
@section('columnas')
    @if (!$proyectos->isEmpty())
        @foreach ($columnas as $columna)
            <th> {{ $columna }} </th>
        @endforeach
        <th>Acciones</th>
    @else
        @php
            echo "<script>
                document.getElementById('tabla_datos').style.display = 'none';
            </script>";
        @endphp
    @endif
@endsection


<!-- Se carga el contenido de la tabla -->
@section('datos')
    {{-- Se manejan los mensajes de error --}}
    @if (session('error'))
        <div id="error-message" style="text-align: center; background-color: rgb(155, 38, 38); color: white; ">
            <p style="font-size: 15px">{{ session('error') }}</p>
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('error-message').style.display = 'none';
            }, 10000);
        </script>
    @endif

    @if ($proyectos->isEmpty())
        <p style="text-align: center;">No hay registros</p>
    @else
        @php
            $proyectosArray = $proyectos->toArray()['data'] ?? $proyectos->toArray(); // Compatible con paginación de Laravel y arrays
            $segmento = 20;
            $pagina = request()->get('pagina', 1);
            $inicio = ($pagina - 1) * $segmento;
            $proyectos_pagina = array_slice($proyectosArray, $inicio, $segmento);
        @endphp

        @foreach ($proyectos_pagina as $proyectoData)
            @php
                // Para manejar tanto colecciones de objetos como arrays asociativos
                $proyecto = is_array($proyectoData) ? (object) $proyectoData : $proyectoData;
            @endphp
            <tr>
                <td>
                    <span class="custom-checkbox">
                        <input type="checkbox" class="checkbox" name="options[]" value="{{ $proyecto->clave_proyecto }}">
                        <label></label>
                    </span>
                </td>
                <td class="clave_proyecto">{{ $proyecto->clave_proyecto }}</td>
                <td class="nombre">{{ $proyecto->nombre }}</td>
                <td class="nombre_descriptivo">{{ $proyecto->nombre_descriptivo }}</td>
                <td>{{ $categorias->firstWhere('idCategoria', $proyecto->categoria)->nombre ?? 'N/A' }}</td>
                <td>{{ $tipos->firstWhere('idTipo', $proyecto->tipo)->nombre ?? 'N/A' }}</td>
                <td>{{ $etapas->firstWhere('idEtapa', $proyecto->etapa)->nombre ?? 'N/A' }}</td>
                @if ($proyecto->video && $proyecto->video !== 'No' && $proyecto->video !== 'no')
                    <td class="video"><a href="{{ $proyecto->video }}" target="_blank">Ver Video</a></td>
                @else
                    <td class="video">Sin Video</td>
                @endif
                <td>{{ $proyecto->fecha_agregado }}</td>
                <td>
                    {{-- NUEVO: Botón de Ver (ojo) añadido --}}
                    <a href="#viewEmployeeModal" class="view" data-toggle="modal"
                        data-clave-proyecto="{{ $proyecto->clave_proyecto }}" 
                        data-nombre="{{ $proyecto->nombre }}" 
                        data-nombre_descriptivo="{{ $proyecto->nombre_descriptivo }}"
                        data-descripcion="{{ $proyecto->descripcion }}"
                        data-categoria_nombre="{{ $categorias->firstWhere('idCategoria', $proyecto->categoria)->nombre ?? 'N/A' }}" 
                        data-tipo_nombre="{{ $tipos->firstWhere('idTipo', $proyecto->tipo)->nombre ?? 'N/A' }}"
                        data-etapa_nombre="{{ $etapas->firstWhere('idEtapa', $proyecto->etapa)->nombre ?? 'N/A' }}"
                        data-video="{{ $proyecto->video }}"
                        data-fecha_agregado="{{ $proyecto->fecha_agregado }}">
                        <i class="material-icons" data-toggle="tooltip" title="Ver"></i></a>
                    
                    <a href="#editEmployeeModal" class="edit" data-toggle="modal"
                        data-clave-proyecto="{{ $proyecto->clave_proyecto }}" 
                        data-nombre="{{ $proyecto->nombre }}" 
                        data-nombre_descriptivo="{{ $proyecto->nombre_descriptivo }}"
                        data-descripcion="{{ $proyecto->descripcion }}"
                        data-categoria="{{ $proyecto->categoria }}" 
                        data-tipo="{{ $proyecto->tipo }}"
                        data-etapa="{{ $proyecto->etapa }}" 
                        data-video="{{ $proyecto->video }}">
                        <i class="material-icons" data-toggle="tooltip" title="Editar"></i></a>
                        
                    <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"
                        data-clave-proyecto="{{ $proyecto->clave_proyecto }}"><i class="material-icons"
                            data-toggle="tooltip" title="Eliminar"></i></a>
                </td>
            </tr>
        @endforeach

        @php
            $totalPaginas = ceil(count($proyectosArray) / $segmento);
        @endphp

        @section('lista_total_paginas')
            <ul class="pagination">
                @for ($i = 1; $i <= $totalPaginas; $i++)
                    <li class="page-item {{ $i == $pagina ? 'active' : '' }}"><a href="?pagina={{ $i }}" class="page-link">{{ $i }}</a></li>
                @endfor
            </ul>
        @endsection

        @section('paginacion')
            <ul class="pagination">
                <li class="page-item {{ $pagina == 1 ? 'disabled' : '' }}">
                    <a href="{{ $pagina == 1 ? '#' : '?pagina=' . ($pagina - 1) }}" class="page-link">Anterior</a>
                </li>
                @yield('lista_total_paginas')
                <li class="page-item {{ $pagina == $totalPaginas ? 'disabled' : '' }}">
                    <a href="{{ $pagina == $totalPaginas ? '#' : '?pagina=' . ($pagina + 1) }}" class="page-link">Siguiente</a>
                </li>
            </ul>
        @endsection
    @endif
@endsection

{{-- Módulos de Agregar, Editar, Eliminar... (La mayoría sin cambios significativos) --}}

{{-- Modulo agregar --}}
@section('modulo_agregar')
    {{-- ... Tu código del módulo agregar queda igual ... --}}
    @parent 
@endsection


{{-- Modulo editar --}}
@section('modulo_editar')
    @if ($proyectos)
        <form action="{{ route('proyectos.editar') }}" method="POST" autocomplete="off">
            @csrf
            <input type="hidden" name="clave_proyecto_editar" id="clave_proyecto_editar">
            {{-- ... El resto de tu formulario de edición ... --}}
            <input type="hidden" name="nombre_mod" id="nombre_mod">
            <input type="hidden" name="nombre_descriptivo_mod" id="nombre_descriptivo_mod">
            {{-- ... etc ... --}}
            <div class="modal-header">
                 <h4 class="modal-title">Editar @yield('administrar_s')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                {{-- ... Todos tus campos de formulario de edición ... --}}
            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                <input type="submit" class="btn btn-info" value="Guardar">
            </div>
        </form>

        <script>
            $(document).ready(function() {
                $('a.edit').click(function() {
                    var claveProyecto = $(this).data('clave-proyecto');
                    var nombre = $(this).data('nombre');
                    var nombreDescriptivo = $(this).data('nombre_descriptivo'); // CORREGIDO
                    var descripcion = $(this).data('descripcion');
                    var categoria = $(this).data('categoria');
                    var tipo = $(this).data('tipo');
                    var etapa = $(this).data('etapa');
                    var video = $(this).data('video');

                    // Llenar campos del formulario
                    $('#editEmployeeModal #clave_proyecto_campo').val(claveProyecto);
                    $('#editEmployeeModal #nombre_campo').val(nombre);
                    $('#editEmployeeModal #nombre_descriptivo_campo').val(nombreDescriptivo);
                    $('#editEmployeeModal #descripcion_campo').val(descripcion);
                    $('#editEmployeeModal select[name="categoria_campo_nombre"]').val(categoria);
                    $('#editEmployeeModal select[name="tipo_campo_nombre"]').val(tipo);
                    $('#editEmployeeModal select[name="etapa_campo_nombre"]').val(etapa);
                    $('#editEmployeeModal #video_campo').val(video);

                    // Llenar campos ocultos
                    $('#clave_proyecto_editar').val(claveProyecto);
                    $('#clave_proyecto_mod').val(claveProyecto);
                    $('#nombre_mod').val(nombre);
                    // ANTES: $('#nombre_descriptivo_mod').val(nombre); -> INCORRECTO
                    $('#nombre_descriptivo_mod').val(nombreDescriptivo); // CORREGIDO
                    $('#descripcion_mod').val(descripcion);
                    $('#categoria_mod').val(categoria);
                    $('#tipo_mod').val(tipo);
                    $('#etapa_mod').val(etapa);
                    $('#video_mod').val(video);
                    
                    // ... Tus listeners de cambio de campos ...
                });
            });
        </script>
        @yield('script_validacion_campos')
    @endif
@endsection


{{-- Modulo eliminar --}}
@section('modulo_eliminar')
    @parent
    {{-- Tu código queda igual --}}
@endsection


{{-- Modulo eliminar para multiples registros --}}
@section('modulo_eliminar_multiple')
    @parent
    {{-- Tu código queda igual --}}
@endsection


{{-- NUEVO: Módulo para ver detalles del proyecto --}}
@section('modulo_ver')
    <div class="modal-header">
        <h4 class="modal-title">Detalles del @yield('administrar_s')</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <h5>Clave del Proyecto</h5>
            <p id="view_clave_proyecto"></p>
        </div>
        <div class="form-group">
            <h5>Nombre</h5>
            <p id="view_nombre"></p>
        </div>
        <div class="form-group">
            <h5>Nombre Descriptivo</h5>
            <p id="view_nombre_descriptivo"></p>
        </div>
        <div class="form-group">
            <h5>Descripción</h5>
            <p id="view_descripcion" style="white-space: pre-wrap;"></p>
        </div>
        <div class="form-group">
            <h5>Categoría</h5>
            <p id="view_categoria"></p>
        </div>
        <div class="form-group">
            <h5>Tipo</h5>
            <p id="view_tipo"></p>
        </div>
        <div class="form-group">
            <h5>Etapa</h5>
            <p id="view_etapa"></p>
        </div>
        <div class="form-group">
            <h5>Video</h5>
            <a id="view_video_link" href="#" target="_blank"></a>
            <p id="view_no_video" style="display: none;">Sin video disponible</p>
        </div>
        <div class="form-group">
            <h5>Fecha de Registro</h5>
            <p id="view_fecha_agregado"></p>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" class="btn btn-info" data-dismiss="modal" value="Cerrar">
    </div>

    {{-- NUEVO: Script para poblar el modal de visualización --}}
    <script>
        $(document).ready(function() {
            $('a.view').click(function() {
                // Obtener datos del botón presionado
                var claveProyecto = $(this).data('clave-proyecto');
                var nombre = $(this).data('nombre');
                var nombreDescriptivo = $(this).data('nombre_descriptivo');
                var descripcion = $(this).data('descripcion');
                var categoriaNombre = $(this).data('categoria_nombre');
                var tipoNombre = $(this).data('tipo_nombre');
                var etapaNombre = $(this).data('etapa_nombre');
                var video = $(this).data('video');
                var fechaAgregado = $(this).data('fecha_agregado');

                // Poblar el modal con los datos
                $('#view_clave_proyecto').text(claveProyecto);
                $('#view_nombre').text(nombre);
                $('#view_nombre_descriptivo').text(nombreDescriptivo);
                $('#view_descripcion').text(descripcion);
                $('#view_categoria').text(categoriaNombre);
                $('#view_tipo').text(tipoNombre);
                $('#view_etapa').text(etapaNombre);
                $('#view_fecha_agregado').text(fechaAgregado);

                // Manejar el enlace del video
                if (video && video !== 'No' && video !== 'no') {
                    $('#view_video_link').attr('href', video).text('Ver video en una nueva pestaña').show();
                    $('#view_no_video').hide();
                } else {
                    $('#view_video_link').hide();
                    $('#view_no_video').show();
                }
            });
        });
    </script>
@endsection
