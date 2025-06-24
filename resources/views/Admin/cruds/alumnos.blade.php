<!-- Se extiende de la clase layout en CRUDs (solamente para CRUDs) -->
@extends('layouts.layoutCrud')

<!-- Se recibe la variable del título que tendrá la página en la pestaña -->
@section('titulo', $titulo)

<!-- ¿Qué se va a administrar en plural? -->
@section('administrar', 'Alumnos')

<!-- ¿Qué se va a administrar en singular? -->
@section('administrar_s', 'Alumno')

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
                    if (!document.getElementById('no_control_eliminar_' + $(this).val())) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'no_control_eliminar_' + $(this).val();
                        input.id = 'no_control_eliminar_' + $(this).val();
                        input.value = $(this).val();
                        document.getElementById('formulario_eliminar_multiple').appendChild(input);
                        // console.log('Se agregó el input: ' + input.name);
                    }

                });

                // en caso de que se deseleccione, se elimina el input oculto
                $('table tbody input[type="checkbox"]:not(:checked)').each(function() {
                    if (document.getElementById('no_control_eliminar_' + $(this).val())) {
                        document.getElementById('no_control_eliminar_' + $(this).val()).remove();
                        // console.log('Se eliminó el input: ' + 'no_control_eliminar_' + $(this).val());
                    }

                    //console.log('Numeros de control seleccionados: ' + numerosControlSeleccionados);
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
        document.querySelectorAll('.no_control').forEach(function(element) {
            element.addEventListener('input', function() {
                var valor = this.value;
                var valorNumerico = valor.replace(/\D/g, '');
                //No permitir más de 8 dígitos
                valorNumerico = valorNumerico.substring(0, 8);
                this.value = valorNumerico;
            });
        });

        document.querySelectorAll('.nombre').forEach(function(element) {
            element.addEventListener('input', function() {
                var valor = this.value;

                // Sólo acepta Letras Mayúsculas y Minúsculas
                var valorLimpio = valor.replace(/[^a-zA-Z\sáéíóúÁÉÍÓÚñÑ]+/g, '').replace(/\s{2,}/g, ' ');
                this.value = valorLimpio;
            });
        });
    </script>
@endsection

<!-- Se recibe la lista de columnas a mostrar (nombres) -->
@section('columnas')

    @if (!$alumnos->isEmpty())
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

    @if ($alumnos->isEmpty())
        <p style="text-align: center;">No hay registros</p>
    @else
        @php
            $alumnos = $alumnos->toArray(); // Convert $alumnos to an array
            $segmento = 20; // Número de registros por página
            $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Página actual
            $inicio = ($pagina - 1) * $segmento; // Registro inicial de la página actual
            $alumnos_pagina = array_slice($alumnos, $inicio, $segmento); // Obtener los alumnos de la página actual
        @endphp

        @foreach ($alumnos_pagina as $alumno)
            <tr>
                <td>
                    <span class="custom-checkbox">
                        <input type="checkbox" class="checkbox" name="options[]" value="{{ $alumno->no_control }}">
                        <label></label>
                    </span>
                </td>
                <td class="no_control">{{ $alumno->no_control }}</td>
                <td class="nombre">{{ $alumno->nombre }}</td>
                <td class="carrera">{{ $alumno->carrera }}</td>
                <td class="correo_institucional">{{ $alumno->correo_institucional }}</td>
                <td style="text-align: center" class="semestre"> {{ $alumno->semestre}}</td>
                <td style="text-align: center" class="telefono"> {{ $alumno->telefono}}</td>
                <td>{{ $alumno->fecha_agregado }}</td>
                <td>
                    <a href="#editEmployeeModal" class="edit" data-toggle="modal"
                        data-no-control="{{ $alumno->no_control }}" data-nombre="{{ $alumno->nombre }}"
                        data-carrera="{{ $alumno->carrera }}" data-correo-institucional={{ $alumno->correo_institucional }} 
                        data-telefono="{{$alumno->telefono}}" data-semestre="{{$alumno->semestre}}">
                        <i class="material-icons" data-toggle="tooltip" title="Editar">&#xE254;</i></a>
                    <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"
                        data-no-control="{{ $alumno->no_control }}"><i class="material-icons" data-toggle="tooltip"
                            title="Eliminar">&#xE872;</i></a>
                </td>
            </tr>
        @endforeach


        @php
            $totalPaginas = ceil(count($alumnos) / $segmento); // Total de páginas
        @endphp

        <!-- Mostrar enlaces de paginación -->
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

    <form action="{{ route('alumnos.agregar') }}" method="POST">
        {{-- Muy importante la directiva siguiente. --}}
        @csrf

        <div class="modal-header">
            <h4 class="modal-title">Agregar @yield('administrar_s')</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label>No. Control</label>
                <input id="no_control_agregar" name="no_control_agregar" type="text" class="form-control no_control" pattern="[0-9]{8}" required>
            </div>
            <div class="form-group">
                <label>Nombre</label>
                <input id="nombre_agregar" name="nombre_agregar" type="text" class="form-control nombre" maxlength="50" required>
            </div>
            <div class="form-group">
                <label>Carrera</label>
                <select name="carrera_agregar" class="form-control" required>
                    @foreach ($carreras as $carrera)
                        <option value="{{ $carrera->nombre }}" class="form-control"> {{ $carrera->nombre }} </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Correo institucional</label>
                <input id="correo_institucional_agregar" name="correo_agregar" type="email" class="form-control correo_institucional" maxlength="50" required>
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input id="telefono_agregar" name="telefono_agregar" type="text" class="form-control telefono" pattern="[0-9]{10}" required>
            </div>
            <div class="form-group">
                <label>Semestre</label>
                <input id="semestre_agregar" name="semestre_agregar" type="number" class="form-control semestre" min="1" max="12" required>
            </div>
        </div>

        <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-success" value="Agregar">
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $('input[type="button"]').click(function() {
                $('#no_control_agregar').val('');
                $('#nombre_agregar').val('');
                $('select[name="carrera_agregar"]').prop('selectedIndex', -1);
                $('#correo_institucional_agregar').val('');
            });
        });
    </script>

    @yield('script_validacion_campos')

@endsection

{{-- Modulo editar --}}
@section('modulo_editar')

    @if ($alumnos)
        <form action="{{ route('alumnos.editar') }}" method="POST">
            {{-- Muy importante la directiva siguiente. --}}
            @csrf

            {{-- Aquí se debe guardar el numero de control a enviar al método del controlador para eliminar. --}}
            <input type="hidden" name="no_control_editar" id="no_control_editar">
            <input type="hidden" name="no_control_mod" id="no_control_mod">
            <input type="hidden" name="nombre_mod" id="nombre_mod">
            <input type="hidden" name="carrera_mod" id="carrera_mod">
            <input type="hidden" name="correo_institucional_mod" id="correo_institucional_mod">
            <input type="hidden" name="telefono_mod" id="telefono_mod">
            <input type="hidden" name="semestre_mod" id="semestre_mod">

            <div class="modal-header">
                <h4 class="modal-title">Editar @yield('administrar_s')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>No. Control</label>
                    <input id="no_control_campo" type="text" class="form-control no_control ceditar" value=""  pattern="[0-9]{8}" required>
                </div>
                <div class="form-group">
                    <label>Nombre</label>
                    <input id="nombre_campo" type="text" class="form-control nombre ceditar" value="" required>
                </div>
                <div class="form-group">
                    <label>Carrera</label>
                    <select id="carrera_campo" class="form-control carrera ceditar" required>
                        @foreach ($carreras as $carrera)
                            <option value="{{ $carrera->nombre }}" class="form-control"
                                {{ isset($alumno) && $carrera->nombre == $alumno->carrera ? 'selected' : '' }}> {{ $carrera->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Correo institucional</label>
                    <input id="correo_institucional_campo" type="email" class="form-control correo_institucional ceditar" value=""  required>
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input id="telefono_campo" type="text" class="form-control telefono ceditar" value="" pattern="[0-9]{10}" required>
                </div>
                <div class="form-group">
                    <label>Semestre</label>
                    <input id="semestre_campo" type="number" class="form-control semestre ceditar" value="" min="1" max="12" required>
                </div>
            </div>

            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                <input type="submit" class="btn btn-info" value="Guardar">
            </div>
        </form>

        {{-- Escucha que identifica según el atributo data-no-control el no. control que se va a editar. --}}
        <script>
            // Se ejecuta cuando el documento esté listo
            $(document).ready(function() {
                $('a.edit').click(function() {
                    // Se obtienen los datos del registro a editar
                    var noControlEditar = $(this).data('no-control');
                    var nombre = $(this).data('nombre');
                    var carrera = $(this).data('carrera');
                    var correo_institucional = $(this).data('correo-institucional');
                    var telefono = $(this).data('telefono');
                    var semestre = $(this).data('semestre');

                    $('#no_control_eliminar').val(noControlEditar);

                    // Se llenan los campos del formulario con los datos del registro a editar
                    $('.no_control').val(noControlEditar);
                    $('.nombre').val(nombre);
                    $('.form-control option').removeAttr('selected');
                    $('.form-control option[value="' + carrera + '"]').attr('selected', 'selected');
                    $('.correo_institucional').val(correo_institucional);
                    $('.telefono').val(telefono);
                    $('.semestre').val(semestre);

                    // Se llenan los campos ocultos con los datos del registro a editar
                    $('#no_control_editar').val(noControlEditar);
                    $('#no_control_mod').val(noControlEditar);
                    $('#nombre_mod').val(nombre);
                    $('#carrera_mod').val(carrera);
                    $('#correo_institucional_mod').val(correo_institucional);
                    $('#telefono_mod').val(telefono);
                    $('#semestre_mod').val(semestre);

                    //Listeners para cuando cambien los campos
                    document.getElementById('no_control_campo').addEventListener('change', function() {
                        $('#no_control_mod').val(this.value);
                    });

                    document.getElementById('nombre_campo').addEventListener('change', function() {
                        $('#nombre_mod').val(this.value);
                    });

                    document.getElementById('carrera_campo').addEventListener('change', function() {
                        $('#carrera_mod').val(this.value);
                    });

                    document.getElementById('correo_institucional_campo').addEventListener('change', function() {
                        $('#correo_institucional_mod').val(this.value);
                    });

                    document.getElementById('telefono_campo').addEventListener('change', function() {
                        $('#telefono_mod').val(this.value);
                    });

                    document.getElementById('semestre_campo').addEventListener('change', function() {
                        $('#semestre_mod').val(this.value);
                    });

                });

            });

            // Vaciar los campos al dar clic en "Cancelar"
            $('input[type="button"]').click(function() {
                $('.no_control').val('');
                $('.nombre').val('');
                $('.carrera').prop('selectedIndex', -1);
                $('.correo_institucional').val('');
                $('.telefono').val('');
                $('.semestre').val('');
            });

            // Vaciar los campos al hacer submit del formulario
            $('form').submit(function() {
                $('.no_control.ceditar').val('');
                $('.nombre.ceditar').val('');
                $('.carrera.ceditar').prop('selectedIndex', -1);
                $('.correo_institucional.ceditar').val('');
                $('.telefono.ceditar').val('');
                $('.semestre.ceditar').val('');
            });
        </script>

        @yield('script_validacion_campos')

    @endif
@endsection


{{-- Modulo eliminar --}}
@section('modulo_eliminar')

    <form action="{{ route('alumnos.eliminar') }}" method="POST">
        {{-- Muy importante la directiva siguiente. --}}
        @csrf

        {{-- Aquí se debe guardar el numero de control a enviar al método del controlador para eliminar. --}}
        <input type="hidden" name="no_control_eliminar" id="no_control_eliminar">

        <div class="modal-header">
            <h4 class="modal-title">Eliminar @yield('administrar_s')</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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

    {{-- Escucha que identifica según el atributo data-no-control el no. control que se va a eliminar. --}}
    <script>
        $(document).ready(function() {
            $('a.delete').click(function() {
                var noControlEliminar = $(this).data('no-control');
                $('#no_control_eliminar').val(noControlEliminar);

                //console.log("No control:", noControl);
            });
        });
    </script>

@endsection


{{-- Modulo eliminar para multiples registros --}}
@section('modulo_eliminar_multiple')
    <form id="formulario_eliminar_multiple" action="{{ route('alumnos.eliminarMultiple') }}" method="POST">

        {{-- Muy importante la directiva siguiente. --}}
        @csrf

        <div class="modal-header">
            <h4 class="modal-title">Eliminar @yield('administrar_s')</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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

