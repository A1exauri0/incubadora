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

                    // Si se selecciona un checkbox, se agrega un input oculto al formulario
                    if (!document.getElementById('clave_proyecto_eliminar_' + $(this).val())) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'clave_proyecto_eliminar_' + $(this).val();
                        input.id = 'clave_proyecto_eliminar_' + $(this).val();
                        input.value = $(this).val();
                        document.getElementById('formulario_eliminar_multiple').appendChild(input);
                        // console.log('Se agregó el input: ' + input.name);
                    }

                });

                // en caso de que se deseleccione, se elimina el input oculto
                $('table tbody input[type="checkbox"]:not(:checked)').each(function() {
                    if (document.getElementById('clave_proyecto_eliminar_' + $(this).val())) {
                        document.getElementById('clave_proyecto_eliminar_' + $(this).val()).remove();
                        // console.log('Se eliminó el input: ' + 'clave_proyecto_eliminar_' + $(this).val());
                    }

                    //Bloque de depuración----
                    //console.log('Numeros de control seleccionados: ' + numerosControlSeleccionados);
                    //Fin de bloque de depuración----
                });

                boton_eliminar = document.getElementById('btnEliminar');
                //console.log("botoneliminar: ", boton_eliminar);

                if (numerosControlSeleccionados.length === 0) { //Si está vacío el botón se inhabilita
                    boton_eliminar.classList.add('boton-deshabilitado');
                } else { //Si tiene al menos un elemento, el botón se habilita.
                    boton_eliminar.classList.remove('boton-deshabilitado');
                }
            }


            // Activar tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // Seleccionar/Deseleccionar checkboxes
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

            // Actualizar el arreglo cuando se carga la página
            actualizarNumerosControlSeleccionados();
        });
    </script>
@endsection

{{-- Script para validación de campos --}}
@section('script_validacion_campos')
    <script>
        document.querySelectorAll('.clave_proyecto').forEach(function(element) {
            element.addEventListener('input', function() {
                var valor = this.value;
                var valorNumerico = valor.replace(/[^A-Z-0-9]+/g, '').replace(/\s{2,}/g, ' ');
                //No permitir más de 8 dígitos
                valorNumerico = valorNumerico.substring(0, 13);
                this.value = valorNumerico;
            });
        });

        document.querySelectorAll('.nombre').forEach(function(element) {
            element.addEventListener('input', function() {
                var valor = this.value;

                // Solo acepta Letras Mayúsculas y Minúsculas, Números, Espacios, ÁÉÍÓÚ, áéíóú, : - y "
                var valorLimpio = valor.replace(/[^a-zA-Z0-9\sáéíóúÁÉÍÓÚ:\-"]/g, '').replace(/\s{2,}/g,
                ' ');
                this.value = valorLimpio;
            });
        });

                document.querySelectorAll('.nombre_descriptivo').forEach(function(element) {
            element.addEventListener('input', function() {
                var valor = this.value;

                // Solo acepta Letras Mayúsculas y Minúsculas, Números, Espacios, ÁÉÍÓÚ, áéíóú, : - y "
                var valorLimpio = valor.replace(/[^a-zA-Z0-9\sáéíóúÁÉÍÓÚ:\-"]/g, '').replace(/\s{2,}/g,
                ' ');
                this.value = valorLimpio;
            });
        });

        document.querySelectorAll('.descripcion').forEach(function(element) {
            element.addEventListener('input', function() {
                var valor = this.value;

                // Sólo acepta Letras Mayúsculas y Minúsculas
                var valorLimpio = valor.replace(/[^a-zA-Z0-9\sáéíóúÁÉÍÓÚ]+/g, '').replace(/\s{2,}/g, ' ');
                this.value = valorLimpio;
            });
        });

        document.querySelectorAll('.video').forEach(function(element) {
            element.addEventListener('input', function() {
                var valor = this.value;

                // Solo acepta Letras Mayúsculas y Minúsculas, Números, "/", ".", ":", "_", "\"
                var valorLimpio = valor.replace(/[^a-zA-Z0-9\sáéíóúÁÉÍÓÚ\/\.\:_\\]+/g, '').replace(
                    /\s{2,}/g, ' ');
                this.value = valorLimpio;
            });
        });

        // NUEVOS CAMPOS: Validaciones de caracteres
        document.querySelectorAll('.area_aplicacion').forEach(function(element) {
            element.addEventListener('input', function() {
                var valor = this.value;
                // Solo acepta letras, números, espacios y los caracteres comunes para descripciones
                var valorLimpio = valor.replace(/[^a-zA-Z0-9\sáéíóúÁÉÍÓÚ().,;-]/g, '').replace(/\s{2,}/g, ' ');
                this.value = valorLimpio.substring(0, 50); // Limita a 50 caracteres
            });
        });

        document.querySelectorAll('.naturaleza_tecnica').forEach(function(element) {
            element.addEventListener('input', function() {
                var valor = this.value;
                // Solo acepta letras, números, espacios y los caracteres comunes para descripciones
                var valorLimpio = valor.replace(/[^a-zA-Z0-9\sáéíóúÁÉÍÓÚ().,;-]/g, '').replace(/\s{2,}/g, ' ');
                this.value = valorLimpio.substring(0, 50); // Limita a 50 caracteres
            });
        });

        document.querySelectorAll('.objetivo').forEach(function(element) {
            element.addEventListener('input', function() {
                var valor = this.value;
                // Solo acepta letras, números, espacios y los caracteres comunes para descripciones
                var valorLimpio = valor.replace(/[^a-zA-Z0-9\sáéíóúÁÉÍÓÚ().,;-]/g, '').replace(/\s{2,}/g, ' ');
                this.value = valorLimpio.substring(0, 255); // Limita a 255 caracteres
            });
        });
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
            }, 10000); // Hide the error message after 5 seconds
        </script>
    @endif

    @if ($proyectos->isEmpty())
        <p style="text-align: center;">No hay registros</p>
    @else
        @php
            $proyectos = $proyectos->toArray(); // Convert $proyectos to an array
            $segmento = 20; // Número de registros por página
            $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Página actual
            $inicio = ($pagina - 1) * $segmento; // Registro inicial de la página actual
            $proyectos_pagina = array_slice($proyectos, $inicio, $segmento); // Obtener los proyectos de la página actual
        @endphp

        @foreach ($proyectos_pagina as $proyecto)
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
                @php
                    // Obtener nombres de las categorías, tipos y etapas para pasarlos a los data-attributes
                    $categoriaNombre = '';
                    foreach ($categorias as $categoria) {
                        if ($proyecto->categoria === $categoria->idCategoria) {
                            $categoriaNombre = $categoria->nombre;
                            echo '<td class="categoria">' . $categoria->nombre . '</td>';
                            break;
                        }
                    }
                    $tipoNombre = '';
                    foreach ($tipos as $tipo) {
                        if ($proyecto->tipo === $tipo->idTipo) {
                            $tipoNombre = $tipo->nombre;
                            echo '<td class="tipo">' . $tipo->nombre . '</td>';
                            break;
                        }
                    }
                    $etapaNombre = '';
                    foreach ($etapas as $etapa) {
                        if ($proyecto->etapa === $etapa->idEtapa) {
                            $etapaNombre = $etapa->nombre;
                            echo '<td class="etapa">' . $etapa->nombre . '</td>';
                            break;
                        }
                    }
                @endphp
                @if ($proyecto->video !== null && $proyecto->video !== 'No' && $proyecto->video !== 'no')
                    <td class="video"><a href="{{ $proyecto->video }}" target="_blank">Video</a> </td>
                @else
                    <td class="video">Sin Video</td>
                @endif
                <td>{{ $proyecto->fecha_agregado }}</td>
                <td>
                    {{-- INICIO DEL NUEVO CONTENEDOR FLEXBOX --}}
                    <div class="action-buttons">
                        {{-- Botón para ver detalles (NUEVO) --}}
                        <a href="#viewProjectModal" class="view" data-toggle="modal"
                            data-clave-proyecto="{{ $proyecto->clave_proyecto }}"
                            data-nombre="{{ $proyecto->nombre }}"
                            data-nombre_descriptivo="{{ $proyecto->nombre_descriptivo }}"
                            data-descripcion="{{ $proyecto->descripcion }}"
                            data-categoria-id="{{ $proyecto->categoria }}"
                            data-categoria-nombre="{{ $categoriaNombre }}"
                            data-tipo-id="{{ $proyecto->tipo }}"
                            data-tipo-nombre="{{ $tipoNombre }}"
                            data-etapa-id="{{ $proyecto->etapa }}"
                            data-etapa-nombre="{{ $etapaNombre }}"
                            data-video="{{ $proyecto->video }}"
                            data-fecha-agregado="{{ $proyecto->fecha_agregado }}"
                            {{-- NUEVOS CAMPOS --}}
                            data-area-aplicacion="{{ $proyecto->area_aplicacion }}"
                            data-naturaleza-tecnica="{{ $proyecto->naturaleza_tecnica }}"
                            data-objetivo="{{ $proyecto->objetivo }}">
                            <i class="material-icons" data-toggle="tooltip" title="Ver Detalles"></i> {{-- Icono de ojito --}}
                        </a>
                        <a href="#editEmployeeModal" class="edit" data-toggle="modal"
                            data-descripcion="{{ $proyecto->descripcion }}"
                            data-clave-proyecto="{{ $proyecto->clave_proyecto }}"
                            data-nombre="{{ $proyecto->nombre }}"
                            data-nombre_descriptivo="{{ $proyecto->nombre_descriptivo }}"
                            data-categoria="{{ $proyecto->categoria }}"
                            data-tipo="{{ $proyecto->tipo }}"
                            data-etapa="{{ $proyecto->etapa }}"
                            data-video="{{ $proyecto->video }}"
                            {{-- NUEVOS CAMPOS --}}
                            data-area-aplicacion="{{ $proyecto->area_aplicacion }}"
                            data-naturaleza-tecnica="{{ $proyecto->naturaleza_tecnica }}"
                            data-objetivo="{{ $proyecto->objetivo }}">
                            <i class="material-icons" data-toggle="tooltip" title="Editar"></i></a>
                        <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"
                            data-clave-proyecto="{{ $proyecto->clave_proyecto }}"><i class="material-icons"
                                data-toggle="tooltip" title="Eliminar"></i></a>
                    </div>
                    {{-- FIN DEL NUEVO CONTENEDOR FLEXBOX --}}
                </td>
            </tr>
        @endforeach


        @php
            $totalPaginas = ceil(count($proyectos) / $segmento); // Total de páginas
        @endphp

        <!-- Mostrar videos de paginación -->
        @section('lista_total_paginas')
            <ul class="pagination">
                @for ($i = 1; $i <= $totalPaginas; $i++)
                    <li class="page-item {{ $i == $pagina ? 'active' : '' }}"><a href="?pagina={{ $i }}"
                            class="page-link">{{ $i }}</a></li>
                @endfor
            </ul>
        @endsection

        {{-- Muestra y añade la funcionalidad de los botones siguiente y anterior --}}
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

{{-- Modulo agregar --}}
@section('modulo_agregar')

    <form action="{{ route('proyectos.agregar') }}" method="POST" autocomplete="off">
        {{-- Muy importante la directiva siguiente. --}}
        @csrf

        {{-- **ELIMINADOS:** Los hidden inputs se han quitado de aquí --}}
        {{-- porque los campos visibles ahora enviarán sus valores directamente. --}}

        <div class="modal-header">
            <h4 class="modal-title">Agregar @yield('administrar_s')</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label>Clave del Proyecto</label>
                {{-- CAMBIO: El 'name' ahora coincide con lo que el controlador espera directamente --}}
                <input id="clave_proyecto_agregar_campo" name="clave_proyecto_agregar" type="text"
                    class="form-control clave_proyecto" pattern="[A-Z0-9\-]{13}" required>
            </div>
            <div class="form-group">
                <label>Nombre</label>
                {{-- CAMBIO: El 'name' ahora coincide con lo que el controlador espera directamente --}}
                <input id="nombre_agregar_campo" name="nombre_agregar" type="text" class="form-control nombre"
                    maxlength="50" pattern="[A-Za-z0-9\-: ]{1,50}" required>
            </div>
            <div class="form-group">
                <label>Nombre Descriptivo</label>
                {{-- CAMBIO: El 'name' ahora coincide con lo que el controlador espera directamente --}}
                <input id="nombre_descriptivo_agregar_campo" name="nombre_descriptivo_agregar" type="text" class="form-control nombre_descriptivo"
                    maxlength="50" pattern="[A-Za-z0-9\-: ]{1,50}" required>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                {{-- CAMBIO: El 'name' ahora coincide con lo que el controlador espera directamente --}}
                <textarea id="descripcion_agregar_campo" name="descripcion_agregar" class="form-control descripcion" maxlength="800"
                    rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label>Categoria</label>
                {{-- CAMBIO: El 'name' ahora coincide con lo que el controlador espera directamente --}}
                <select name="categoria_agregar" class="form-control" required> {{-- Era 'categoria_agregar_nombre', ahora 'categoria_agregar' --}}
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->idCategoria }}" class="form-control"> {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Tipo</label>
                {{-- CAMBIO: El 'name' ahora coincide con lo que el controlador espera directamente --}}
                <select name="tipo_agregar" class="form-control" required> {{-- Era 'tipo_agregar_nombre', ahora 'tipo_agregar' --}}
                    @foreach ($tipos as $tipo)
                        <option value="{{ $tipo->idTipo }}" class="form-control"> {{ $tipo->nombre }} </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Etapa</label>
                {{-- CAMBIO: El 'name' ahora coincide con lo que el controlador espera directamente --}}
                <select name="etapa_agregar" class="form-control" required> {{-- Era 'etapa_agregar_nombre', ahora 'etapa_agregar' --}}
                    @foreach ($etapas as $etapa)
                        <option value="{{ $etapa->idEtapa }}" class="form-control"> {{ $etapa->nombre }} </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Video</label>
                {{-- CAMBIO: El 'name' ahora coincide con lo que el controlador espera directamente --}}
                <input id="video_agregar_campo" type="text" name="video_agregar" class="form-control video"
                    placeholder="URL del video (Youtube, Google Drive, etc.)"
                    pattern="^(https:\/\/(www\.)?(youtube\.com\/|drive\.google\.com\/).*)$">
            </div>
            {{-- NUEVOS CAMPOS DE FORMULARIO --}}
            <div class="form-group">
                <label>Área de Aplicación</label>
                {{-- CAMBIO: El 'name' ahora coincide con lo que el controlador espera directamente --}}
                <input id="area_aplicacion_agregar_campo" name="area_aplicacion_agregar" type="text"
                    class="form-control area_aplicacion" maxlength="50">
            </div>
            <div class="form-group">
                <label>Naturaleza Técnica</label>
                {{-- CAMBIO: El 'name' ahora coincide con lo que el controlador espera directamente --}}
                <input id="naturaleza_tecnica_agregar_campo" name="naturaleza_tecnica_agregar" type="text"
                    class="form-control naturaleza_tecnica" maxlength="50">
            </div>
            <div class="form-group">
                <label>Objetivo</label>
                {{-- CAMBIO: El 'name' ahora coincide con lo que el controlador espera directamente --}}
                <textarea id="objetivo_agregar_campo" name="objetivo_agregar" class="form-control objetivo" maxlength="255"
                    rows="3"></textarea>
            </div>
        </div>

        <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-success" value="Agregar">
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $('#addEmployeeModal').on('show.bs.modal', function() {
                // Limpiar campos al abrir el modal. Los IDs siguen siendo útiles para esto.
                $('#clave_proyecto_agregar_campo').val('');
                $('#nombre_agregar_campo').val('');
                $('#nombre_descriptivo_agregar_campo').val('');
                $('#descripcion_agregar_campo').val('');
                // Establece el selectedIndex en 0 para la primera opción
                $('select[name="categoria_agregar"]').prop('selectedIndex', 0); // Corrección: Nombre del select
                $('select[name="tipo_agregar"]').prop('selectedIndex', 0);     // Corrección: Nombre del select
                $('select[name="etapa_agregar"]').prop('selectedIndex', 0);    // Corrección: Nombre del select
                $('#video_agregar_campo').val('');
                // Limpiar nuevos campos
                $('#area_aplicacion_agregar_campo').val('');
                $('#naturaleza_tecnica_agregar_campo').val('');
                $('#objetivo_agregar_campo').val('');
            });
        });
    </script>

    {{-- Script de configuración inicial para el botón de agregar --}}
    <script>
        $(document).ready(function() {
            // El script que estaba aquí para el click de #btnAgregar
            // para copiar valores a hidden inputs ya NO ES NECESARIO,
            // ya que los campos visibles ahora envían directamente el valor.
            // Los listeners de 'change' y 'input' para los hidden inputs también se vuelven redundantes.
            // Solo se mantiene el listener para el 'click' del botón de apertura del modal
            // (que ya está en el script superior con el 'show.bs.modal').
        });
    </script>

    @yield('script_validacion_campos')

@endsection

{{-- Modulo editar --}}
@section('modulo_editar')

    @if ($proyectos)
        <form action="{{ route('proyectos.editar') }}" method="POST" autocomplete="off">
            {{-- Muy importante la directiva siguiente. --}}
            @csrf

            {{-- Aquí se debe guardar el numero de control a enviar al método del controlador para eliminar. --}}
            <input type="hidden" name="clave_proyecto_editar" id="clave_proyecto_editar">

            <input type="hidden" name="clave_proyecto_mod" id="clave_proyecto_mod">
            <input type="hidden" name="nombre_mod" id="nombre_mod">
            <input type="hidden" name="nombre_descriptivo_mod" id="nombre_descriptivo_mod">
            <input type="hidden" name="descripcion_mod" id="descripcion_mod">
            <input type="hidden" name="categoria_mod" id="categoria_mod">
            <input type="hidden" name="tipo_mod" id="tipo_mod">
            <input type="hidden" name="etapa_mod" id="etapa_mod">
            <input type="hidden" name="video_mod" id="video_mod">
            {{-- NUEVOS CAMPOS OCULTOS --}}
            <input type="hidden" name="area_aplicacion_mod" id="area_aplicacion_mod">
            <input type="hidden" name="naturaleza_tecnica_mod" id="naturaleza_tecnica_mod">
            <input type="hidden" name="objetivo_mod" id="objetivo_mod">

            <div class="modal-header">
                <h4 class="modal-title">Editar @yield('administrar_s')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Clave del Proyecto</label>
                    {{-- Este 'name' se mantiene con _campo porque el hidden input clave_proyecto_mod es el que se usa en el controlador --}}
                    <input id="clave_proyecto_campo" type="text" class="form-control clave_proyecto ceditar"
                        value="" pattern="[0-9A-Z]{13}" required>
                </div>
                <div class="form-group">
                    <label>Nombre</label>
                    <input id="nombre_campo" type="text" class="form-control nombre ceditar" value=""
                        maxlength="50" required>
                </div>
                <div class="form-group">
                    <label>Nombre Descriptivo</label>
                    <input id="nombre_descriptivo_campo" type="text" class="form-control nombre_descriptivo ceditar"
                        value="" maxlength="50" required>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea id="descripcion_campo" name="descripcion_agregar ceditar" class="form-control descripcion" maxlength="200"
                        rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label>Categoria</label>
                    <select name="categoria_campo_nombre" class="form-control" required>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->idCategoria }}" class="form-control"> {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Tipo</label>
                    <select name="tipo_campo_nombre" class="form-control" required>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->idTipo }}" class="form-control"> {{ $tipo->nombre }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Etapa</label>
                    <select name="etapa_campo_nombre" class="form-control" required>
                        @foreach ($etapas as $etapa)
                            <option value="{{ $etapa->idEtapa }}" class="form-control"> {{ $etapa->nombre }} </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Video</label>
                    <input id="video_campo" name="video_agregar ceditar" type="url" class="form-control video"
                        placeholder="URL del video (Youtube, Google Drive)">
                </div>
                {{-- NUEVOS CAMPOS DE FORMULARIO --}}
                <div class="form-group">
                    <label>Área de Aplicación</label>
                    <input id="area_aplicacion_campo" type="text" class="form-control area_aplicacion ceditar" maxlength="50">
                </div>
                <div class="form-group">
                    <label>Naturaleza Técnica</label>
                    <input id="naturaleza_tecnica_campo" type="text" class="form-control naturaleza_tecnica ceditar" maxlength="50">
                </div>
                <div class="form-group">
                    <label>Objetivo</label>
                    <textarea id="objetivo_campo" class="form-control objetivo ceditar" maxlength="255" rows="3"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                <input type="submit" class="btn btn-info" value="Guardar">
            </div>
        </form>

        {{-- Escucha que identifica según el atributo data-clave-proyecto el no. control que se va a editar. --}}
        <script>
            // Se ejecuta cuando el documento esté listo
            $(document).ready(function() {
                $('a.edit').click(function() {
                    // Se obtienen los datos del registro a editar
                    var claveProyecto = $(this).data('clave-proyecto');
                    var nombre = $(this).data('nombre');
                    var nombreDescriptivo = $(this).data('nombre_descriptivo');
                    var descripcion = $(this).data('descripcion');
                    var categoria = $(this).data('categoria');
                    var tipo = $(this).data('tipo');
                    var etapa = $(this).data('etapa');
                    var video = $(this).data('video');
                    // NUEVOS CAMPOS
                    var areaAplicacion = $(this).data('area-aplicacion');
                    var naturalezaTecnica = $(this).data('naturaleza-tecnica');
                    var objetivo = $(this).data('objetivo');

                    // Se llenan los campos del formulario con los datos del registro a editar
                    $('#clave_proyecto_campo').val(claveProyecto); // Usar ID específico para inputs
                    $('#nombre_campo').val(nombre);
                    $('#nombre_descriptivo_campo').val(nombreDescriptivo);
                    $('#descripcion_campo').val(descripcion);
                    $('select[name="categoria_campo_nombre"]').val(categoria);
                    $('select[name="tipo_campo_nombre"]').val(tipo);
                    $('select[name="etapa_campo_nombre"]').val(etapa);
                    $('#video_campo').val(video);
                    // NUEVOS CAMPOS: Llenar en el modal de edición
                    $('#area_aplicacion_campo').val(areaAplicacion);
                    $('#naturaleza_tecnica_campo').val(naturalezaTecnica);
                    $('#objetivo_campo').val(objetivo);


                    // Se llenan los campos ocultos con los datos del registro a editar
                    $('#clave_proyecto_editar').val(claveProyecto); // Clave original
                    $('#clave_proyecto_mod').val(claveProyecto); // Clave actual (por si se modifica)
                    $('#nombre_mod').val(nombre);
                    $('#nombre_descriptivo_mod').val(nombreDescriptivo);
                    $('#descripcion_mod').val(descripcion);
                    $('#categoria_mod').val(categoria);
                    $('#tipo_mod').val(tipo);
                    $('#etapa_mod').val(etapa);
                    $('#video_mod').val(video);
                    // NUEVOS CAMPOS: Llenar ocultos
                    $('#area_aplicacion_mod').val(areaAplicacion);
                    $('#naturaleza_tecnica_mod').val(naturalezaTecnica);
                    $('#objetivo_mod').val(objetivo);

                    //Listeners para cuando cambien los campos y actualizar los hidden inputs
                    document.getElementById('clave_proyecto_campo').addEventListener('input', function() {
                        $('#clave_proyecto_mod').val(this.value);
                    });
                    document.getElementById('nombre_campo').addEventListener('input', function() {
                        $('#nombre_mod').val(this.value);
                    });
                    document.getElementById('nombre_descriptivo_campo').addEventListener('input', function() {
                        $('#nombre_descriptivo_mod').val(this.value);
                    });
                    document.getElementById('descripcion_campo').addEventListener('input', function() {
                        $('#descripcion_mod').val(this.value);
                    });
                    document.querySelector('select[name="categoria_campo_nombre"]').addEventListener('change',
                        function() {
                            $('#categoria_mod').val(this.value);
                        });
                    document.querySelector('select[name="tipo_campo_nombre"]').addEventListener('change',
                        function() {
                            $('#tipo_mod').val(this.value);
                        });
                    document.querySelector('select[name="etapa_campo_nombre"]').addEventListener('change',
                        function() {
                            $('#etapa_mod').val(this.value);
                        });
                    document.getElementById('video_campo').addEventListener('input', function() {
                        $('#video_mod').val(this.value);
                    });
                    // NUEVOS CAMPOS: Listeners para actualizar hidden inputs
                    document.getElementById('area_aplicacion_campo').addEventListener('input', function() {
                        $('#area_aplicacion_mod').val(this.value);
                    });
                    document.getElementById('naturaleza_tecnica_campo').addEventListener('input', function() {
                        $('#naturaleza_tecnica_mod').val(this.value);
                    });
                    document.getElementById('objetivo_campo').addEventListener('input', function() {
                        $('#objetivo_mod').val(this.value);
                    });
                });
            });
            // Las secciones de vaciar campos al cancelar o submit se mantienen comentadas como antes.
        </script>

        @yield('script_validacion_campos')

    @endif
@endsection


{{-- Modulo eliminar --}}
@section('modulo_eliminar')

    <form action="{{ route('proyectos.eliminar') }}" method="POST">
        {{-- Muy importante la directiva siguiente. --}}
        @csrf

        {{-- Aquí se debe guardar el numero de control a enviar al método del controlador para eliminar. --}}
        <input type="hidden" name="clave_proyecto_eliminar" id="clave_proyecto_eliminar">

        <div class="modal-header">
            <h4 class="modal-title">Eliminar @yield('administrar_s')</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <p>¿Seguro que quiere eliminar este registro?</p>
            <p class="text-warning"><small>Esta acción no se podrá deshacer.</small></p>
        </div>
        <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-danger" value="Eliminar">
        </div>
    </form>

    {{-- Escucha que identifica según el atributo data-clave-proyecto el registro que se va a eliminar. --}}
    <script>
        $(document).ready(function() {
            $('a.delete').click(function() {
                var clave_proyecto = $(this).data('clave-proyecto');
                $('#clave_proyecto_eliminar').val(clave_proyecto);

                //console.log("No control:", noControl);
            });
        });
    </script>

@endsection


{{-- Modulo eliminar para multiples registros --}}
@section('modulo_eliminar_multiple')
    <form id="formulario_eliminar_multiple" action="{{ route('proyectos.eliminarMultiple') }}" method="POST">

        {{-- Muy importante la directiva siguiente. --}}
        @csrf

        <div class="modal-header">
            <h4 class="modal-title">Eliminar @yield('administrar_s')</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <p>¿Seguro que quieres eliminar estos registros?</p>
            <p class="text-warning"><small>Esta acción no se podrá deshacer.</small></p>
        </div>
        <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-danger" value="Eliminar">
        </div>
    </form>

@endsection

{{-- NUEVO: Modulo para ver detalles del proyecto --}}
@section('modulo_ver')
    <div class="modal-header">
        <h4 class="modal-title">Detalles del Proyecto</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        <p><strong>Clave del Proyecto:</strong> <span id="view_clave_proyecto"></span></p>
        <p><strong>Nombre:</strong> <span id="view_nombre"></span></p>
        <p><strong>Nombre Descriptivo:</strong> <span id="view_nombre_descriptivo"></span></p>
        <p><strong>Descripción:</strong> <span id="view_descripcion" style="white-space: pre-wrap;"></span></p>
        <p><strong>Categoría:</strong> <span id="view_categoria"></span></p>
        <p><strong>Tipo:</strong> <span id="view_tipo"></span></p>
        <p><strong>Etapa:</strong> <span id="view_etapa"></span></p>
        <p><strong>Video:</strong> <span id="view_video_container"><a id="view_video_link" href="#" target="_blank"></a></span></p>
        <p><strong>Fecha de Agregado:</strong> <span id="view_fecha_agregado"></span></p>
        {{-- NUEVOS CAMPOS A VISUALIZAR --}}
        <p><strong>Área de Aplicación:</strong> <span id="view_area_aplicacion"></span></p>
        <p><strong>Naturaleza Técnica:</strong> <span id="view_naturaleza_tecnica"></span></p>
        <p><strong>Objetivo:</strong> <span id="view_objetivo" style="white-space: pre-wrap;"></span></p>
    </div>
    <div class="modal-footer">
        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cerrar">
    </div>

    <script>
        $(document).ready(function() {
            $('a.view').click(function() {
                var claveProyecto = $(this).data('clave-proyecto');
                var nombre = $(this).data('nombre');
                var nombreDescriptivo = $(this).data('nombre_descriptivo');
                var descripcion = $(this).data('descripcion');
                var categoriaNombre = $(this).data('categoria-nombre');
                var tipoNombre = $(this).data('tipo-nombre');
                var etapaNombre = $(this).data('etapa-nombre');
                var video = $(this).data('video');
                var fechaAgregado = $(this).data('fecha-agregado');
                // NUEVOS CAMPOS
                var areaAplicacion = $(this).data('area-aplicacion');
                var naturalezaTecnica = $(this).data('naturaleza-tecnica');
                var objetivo = $(this).data('objetivo');

                $('#view_clave_proyecto').text(claveProyecto);
                $('#view_nombre').text(nombre);
                $('#view_nombre_descriptivo').text(nombreDescriptivo);
                $('#view_descripcion').text(descripcion);
                $('#view_categoria').text(categoriaNombre);
                $('#view_tipo').text(tipoNombre);
                $('#view_etapa').text(etapaNombre);
                $('#view_fecha_agregado').text(fechaAgregado);
                // NUEVOS CAMPOS: Mostrar en el modal de ver
                $('#view_area_aplicacion').text(areaAplicacion);
                $('#view_naturaleza_tecnica').text(naturalezaTecnica);
                $('#view_objetivo').text(objetivo);


                var videoLink = $('#view_video_link');
                if (video && video.trim() !== '' && video.trim().toLowerCase() !== 'no') {
                    videoLink.attr('href', video).text('Ver Video');
                    videoLink.parent().show(); // Muestra el contenedor si hay video
                } else {
                    videoLink.parent().hide(); // Oculta el contenedor si no hay video
                    videoLink.attr('href', '#').text('Sin Video'); // Resetea el texto por si acaso
                }
            });
        });
    </script>
@endsection