@include('Admin.cruds.layouts.header')

<!-- Se extiende de la clase layout en CRUDs (solamente para CRUDs) -->
@extends('Admin.cruds.layouts.layout')

<!-- Se recibe la variable del título que tendrá la página en la pestaña -->
@section('titulo', $titulo)

<!-- ¿Qué se va a administrar en plural? -->
@section('administrar', 'Habilidades')

<!-- ¿Qué se va a administrar en singular? -->
@section('administrar_s', 'Habilidad')

@section('total_registros', $total_registros)

{{-- Script para checkboxes (se integra con el modulo para eliminar multiples registros) --}}
@section('script_checkboxes')
    <script>
        $(document).ready(function() {
            // Arreglo para almacenar los números de control seleccionados
            var habilidadesSeleccionados = [];

            // Función para actualizar el arreglo cuando se selecciona o deselecciona un checkbox
            function actualizarHabilidadSeleccionados() {
                habilidadesSeleccionados = [];
                $('table tbody input[type="checkbox"]:checked').each(function() {
                    habilidadesSeleccionados.push($(this).val());

                    // Si se selecciona un checkbox, se agrega un input oculto al formulario
                    if (!document.getElementById('idHabilidad_eliminar_' + $(this).val())) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'idHabilidad_eliminar_' + $(this).val();
                        input.id = 'idHabilidad_eliminar_' + $(this).val();
                        input.value = $(this).val();
                        document.getElementById('formulario_eliminar_multiple').appendChild(input);
                        // console.log('Se agregó el input: ' + input.name);
                    }

                });

                // en caso de que se deseleccione, se elimina el input oculto
                $('table tbody input[type="checkbox"]:not(:checked)').each(function() {
                    if (document.getElementById('idHabilidad_eliminar_' + $(this).val())) {
                        document.getElementById('idHabilidad_eliminar_' + $(this).val()).remove();
                        // console.log('Se eliminó el input: ' + 'idCategoria_eliminar_' + $(this).val());
                    }

                    //console.log('Numeros de control seleccionados: ' + numerosControlSeleccionados);
                });

                boton_eliminar = document.getElementById('btnEliminar');
                //console.log("botoneliminar: ", boton_eliminar);

                if (habilidadesSeleccionados.length === 0) { //Si está vacío el botón se inhabilita
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
                actualizarHabilidadSeleccionados();
            });
            checkbox.click(function() {
                if (!this.checked) {
                    $("#selectAll").prop("checked", false);
                }
                actualizarHabilidadSeleccionados();
            });

            // Actualizar el arreglo cuando se carga la página
            actualizarHabilidadSeleccionados();
        });
    </script>
@endsection

{{-- Script para validación de campos --}}
@section('script_validacion_campos')
    <script>
        document.querySelectorAll('.idHabilidad').forEach(function(element) {
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
                var valorLimpio = valor.replace(/[^a-zA-Z0-9\sáéíóúÁÉÍÓÚ]+/g, '').replace(/\s{2,}/g, ' ');
                this.value = valorLimpio;
            });
        });
    </script>
@endsection

<!-- Se recibe la lista de columnas a mostrar (nombres) -->
@section('columnas')

    @if (!$habilidades->isEmpty())
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

    @if ($habilidades->isEmpty())
        <p style="text-align: center;">No hay registros</p>
    @else
        @php
            $habilidades = $habilidades->toArray(); // Convert $habilidades to an array
            $segmento = 20; // Número de registros por página
            $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Página actual
            $inicio = ($pagina - 1) * $segmento; // Registro inicial de la página actual
            $habilidades_pagina = array_slice($habilidades, $inicio, $segmento); // Obtener los habilidades de la página actual
        @endphp

        @foreach ($habilidades_pagina as $habilidad)
            <tr>
                <td>
                    <span class="custom-checkbox">
                        <input type="checkbox" class="checkbox" name="options[]" value="{{ $habilidad->idHabilidad }}">
                        <label></label>
                    </span>
                </td>
                <td class="idHabilidad">{{ $habilidad->idHabilidad }}</td>
                <td class="nombre">{{ $habilidad->nombre }}</td>
                <td>
                    <a href="#editEmployeeModal" class="edit" data-toggle="modal"
                        data-idhabilidad="{{ $habilidad->idHabilidad }}" data-nombre="{{ $habilidad->nombre }}">
                        <i class="material-icons" data-toggle="tooltip" title="Editar">&#xE254;</i></a>
                    <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"
                        data-idhabilidad="{{ $habilidad->idHabilidad }}"><i class="material-icons" data-toggle="tooltip"
                            title="Eliminar">&#xE872;</i></a>
                </td>
            </tr>
        @endforeach


        @php
            $totalPaginas = ceil(count($habilidades) / $segmento); // Total de páginas
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

    <form action="{{ route('habilidades.agregar') }}" method="POST">
        {{-- Muy importante la directiva siguiente. --}}
        @csrf

        <div class="modal-header">
            <h4 class="modal-title">Agregar @yield('administrar_s')</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label>Nombre</label>
                <input id="nombre_agregar" name="nombre_agregar" type="text" class="form-control nombre" required>
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
                $('#nombre_agregar').val('');
            });
        });
    </script>

    @yield('script_validacion_campos')

@endsection

{{-- Modulo editar --}}
@section('modulo_editar')

    @if ($habilidades)
        <form action="{{ route('habilidades.editar') }}" method="POST">
            {{-- Muy importante la directiva siguiente. --}}
            @csrf

            {{-- Aquí se debe guardar el numero de control a enviar al método del controlador para eliminar. --}}
            <input type="hidden" name="idHabilidad_editar" id="idHabilidad_editar">

            <input type="hidden" name="nombre_mod" id="nombre_mod">

            <div class="modal-header">
                <h4 class="modal-title">Editar @yield('administrar_s')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre</label>
                    <input id="nombre_campo" type="text" class="form-control nombre ceditar" value="" required>
                </div>
            </div>

            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                <input type="submit" class="btn btn-info" value="Guardar">
            </div>
        </form>

        {{-- Escucha que identifica según el atributo data-idCategoria el no. control que se va a editar. --}}
        <script>
            // Se ejecuta cuando el documento esté listo
            $(document).ready(function() {
                $('a.edit').click(function() {
                    // Se obtienen los datos del registro a editar
                    var idHabilidad = $(this).data('idhabilidad');
                    var nombre = $(this).data('nombre');

                    // Se llenan los campos del formulario con los datos del registro a editar
                    $('.nombre').val(nombre);

                    // Se llenan los campos ocultos con los datos del registro a editar
                    $('#idHabilidad_editar').val(idHabilidad);
                    $('#nombre_mod').val(nombre);

                    //Listeners para cuando cambien los campos
                    document.getElementById('nombre_campo').addEventListener('change', function() {
                        $('#nombre_mod').val(this.value);
                    });

                    //console.log("idCategoria:", idCategoria);
                });

            });

            // Vaciar los campos al dar clic en "Cancelar"
            $('input[type="button"]').click(function() {
                $('.nombre').val('');
            });

            // Vaciar los campos al hacer submit del formulario
            $('form').submit(function() {
                $('.nombre.ceditar').val('');
            });
        </script>

        @yield('script_validacion_campos')

    @endif
@endsection


{{-- Modulo eliminar --}}
@section('modulo_eliminar')

    <form action="{{ route('habilidades.eliminar') }}" method="POST">
        {{-- Muy importante la directiva siguiente. --}}
        @csrf

        {{-- Aquí se debe guardar el numero de control a enviar al método del controlador para eliminar. --}}
        <input type="hidden" name="idHabilidad_eliminar" id="idHabilidad_eliminar">

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

    {{-- Escucha que identifica según el atributo data-idCategoria el no. control que se va a eliminar. --}}
    <script>
        $(document).ready(function() {
            $('a.delete').click(function() {
                var habilidadEliminar = $(this).data('idhabilidad');
                $('#idHabilidad_eliminar').val(habilidadEliminar);

                //console.log("No control:", noControl);
            });
        });
    </script>

@endsection


{{-- Modulo eliminar para multiples registros --}}
@section('modulo_eliminar_multiple')
    <form id="formulario_eliminar_multiple" action="{{ route('habilidades.eliminarMultiple') }}" method="POST">

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