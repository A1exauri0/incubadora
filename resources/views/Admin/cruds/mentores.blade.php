@include('Admin.cruds.layouts.header')

<!-- Se extiende de la clase layout en CRUDs (solamente para CRUDs) -->
@extends('Admin.cruds.layouts.layout')

<!-- Se recibe la variable del título que tendrá la página en la pestaña -->
@section('titulo', $titulo)

<!-- ¿Qué se va a administrar en plural? -->
@section('administrar', 'Mentores')

<!-- ¿Qué se va a administrar en singular? -->
@section('administrar_s', 'Mentor')

@section('total_registros', $total_registros)

{{-- Script para checkboxes (se integra con el modulo para eliminar multiples registros) --}}
@section('script_checkboxes')
    <script>
        $(document).ready(function() {
            // Arreglo para almacenar los números de control seleccionados
            var idMentoresSeleccionados = [];

            // Función para actualizar el arreglo cuando se selecciona o deselecciona un checkbox
            function actualizaridMentoresSeleccionados() {
                idMentoresSeleccionados = [];
                $('table tbody input[type="checkbox"]:checked').each(function() {
                    idMentoresSeleccionados.push($(this).val());

                    // Si se selecciona un checkbox, se agrega un input oculto al formulario
                    if (!document.getElementById('idMentor_eliminar_' + $(this).val())) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'idMentor_eliminar_' + $(this).val();
                        input.id = 'idMentor_eliminar_' + $(this).val();
                        input.value = $(this).val();
                        document.getElementById('formulario_eliminar_multiple').appendChild(input);
                        // console.log('Se agregó el input: ' + input.name);
                    }

                });

                // en caso de que se deseleccione, se elimina el input oculto
                $('table tbody input[type="checkbox"]:not(:checked)').each(function() {
                    if (document.getElementById('idMentor_eliminar_' + $(this).val())) {
                        document.getElementById('idMentor_eliminar_' + $(this).val()).remove();
                        // console.log('Se eliminó el input: ' + 'idMentor_eliminar_' + $(this).val());
                    }

                    // console.log('Numeros de control seleccionados: ' + idMentoresSeleccionados);
                });

                boton_eliminar = document.getElementById('btnEliminar');
                //console.log("botoneliminar: ", boton_eliminar);

                if (idMentoresSeleccionados.length === 0) { //Si está vacío el botón se inhabilita
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
                actualizaridMentoresSeleccionados();
            });
            checkbox.click(function() {
                if (!this.checked) {
                    $("#selectAll").prop("checked", false);
                }
                actualizaridMentoresSeleccionados();
            });

            // Actualizar el arreglo cuando se carga la página
            actualizaridMentoresSeleccionados();
        });
    </script>
@endsection

{{-- Script para validación de campos --}}
@section('script_validacion_campos')
    <script>
        document.querySelectorAll('.idMentor').forEach(function(element) {
            element.addEventListener('input', function() {
                var valor = this.value;
                var valorLimpio = valor.replace(/[^A-Z0-9-]+/g, '');
                valorLimpio = valorLimpio.substring(0, 13);
                this.value = valorLimpio;
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

@section('columnas')
    @if (!$mentores->isEmpty())
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


@section('datos')
    @if ($mentores->isEmpty())
        <p style="text-align: center;">No hay registros</p>
    @else
        @php
            $mentores = $mentores->toArray();
            $segmento = 20;
            $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
            $inicio = ($pagina - 1) * $segmento;
            $mentores_pagina = array_slice($mentores, $inicio, $segmento);
        @endphp

        @foreach ($mentores_pagina as $mentor)
            <tr>
                <td>
                    <span class="custom-checkbox">
                        <input type="checkbox" class="checkbox" name="options[]" value="{{ $mentor->idMentor }}">
                        <label></label>
                    </span>
                </td>
                <td class="idMentor">{{ $mentor->idMentor }}</td>
                <td class="nombre">{{ $mentor->nombre }}</td>
                <td>{{ $mentor->fecha_agregado }}</td>
                <td>
                    <a href="#editEmployeeModal" class="edit" data-toggle="modal"
                        data-idmentor="{{ $mentor->idMentor }}" data-nombre="{{ $mentor->nombre }}">
                        <i class="material-icons" data-toggle="tooltip" title="Editar">&#xE254;</i></a>
                    <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"
                        data-idmentor="{{ $mentor->idMentor }}"><i class="material-icons" data-toggle="tooltip"
                            title="Eliminar">&#xE872;</i></a>
                </td>
            </tr>
        @endforeach

        @php
            $totalPaginas = ceil(count($mentores) / $segmento);
        @endphp

        @section('lista_total_paginas')
            <ul class="pagination">
                @for ($i = 1; $i <= $totalPaginas; $i++)
                    <li class="page-item {{ $i == $pagina ? 'active' : '' }}"><a href="?pagina={{ $i }}"
                            class="page-link">{{ $i }}</a></li>
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


{{-- Modulo agregar --}}
@section('modulo_agregar')

    <form action="{{ route('mentores.agregar') }}" method="POST">
        {{-- Muy importante la directiva siguiente. --}}
        @csrf

        <div class="modal-header">
            <h4 class="modal-title">Agregar @yield('administrar_s')</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label>Nombre</label>
                <input id="nombre_agregar" name="nombre_agregar" type="text" class="form-control nombre" maxlength="50" required>
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

    @if ($mentores)
        <form action="{{ route('mentores.editar') }}" method="POST">
            {{-- Muy importante la directiva siguiente. --}}
            @csrf

            {{-- Aquí se debe guardar el numero de control a enviar al método del controlador para eliminar. --}}
            <input type="hidden" name="idMentor_editar" id="idMentor_editar">
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

        {{-- Escucha que identifica según el atributo data-idMentor el no. control que se va a editar. --}}
        <script>
            // Se ejecuta cuando el documento esté listo
            $(document).ready(function() {
                $('a.edit').click(function() {
                    // Se obtienen los datos del registro a editar
                    var idMentor = $(this).data('idmentor');
                    var nombre = $(this).data('nombre');

                    // Se llenan los campos del formulario con los datos del registro a editar
                    $('.nombre').val(nombre);

                    // Se llenan los campos ocultos con los datos del registro a editar
                    $('#idMentor_editar').val(idMentor);
                    $('#nombre_mod').val(nombre);

                    //Listeners para cuando cambien los campos
                    document.getElementById('nombre_campo').addEventListener('change', function() {
                        $('#nombre_mod').val(this.value);
                    });

                    //console.log("No control:", idMentor);
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

    <form action="{{ route('mentores.eliminar') }}" method="POST">
        {{-- Muy importante la directiva siguiente. --}}
        @csrf

        {{-- Aquí se debe guardar el numero de control a enviar al método del controlador para eliminar. --}}
        <input type="hidden" name="idMentor_eliminar" id="idMentor_eliminar">

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

    {{-- Escucha que identifica según el atributo data-idMentor el no. control que se va a eliminar. --}}
    <script>
        $(document).ready(function() {
            $('a.delete').click(function() {
                var idMentorEliminar = $(this).data('idmentor');
                $('#idMentor_eliminar').val(idMentorEliminar);

                //console.log("No control:", idMentorEliminar);
            });
        });
    </script>

@endsection


{{-- Modulo eliminar para multiples registros --}}
@section('modulo_eliminar_multiple')
    <form id="formulario_eliminar_multiple" action="{{ route('mentores.eliminarMultiple') }}" method="POST">

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