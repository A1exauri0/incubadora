<!-- Se extiende de la clase layout en CRUDs (solamente para CRUDs) -->
@extends('layouts.layoutCrud')

<!-- Se recibe la variable del título que tendrá la página en la pestaña -->
@section('titulo', $titulo)

<!-- ¿Qué se va a administrar en plural? -->
@section('administrar', 'Usuarios')

<!-- ¿Qué se va a administrar en singular? -->
@section('administrar_s', 'Usuario') {{-- Cambiado a singular para mensajes --}}

@section('total_registros', $total_registros)

{{-- Script para checkboxes (se integra con el modulo para eliminar multiples registros) --}}
@section('script_checkboxes')
    <script>
        $(document).ready(function() {
            // Arreglo para almacenar los IDs seleccionados
            var idsSeleccionadas = [];

            // Función para actualizar el arreglo cuando se selecciona o deselecciona un checkbox
            function actualizaridsSeleccionadas() {
                idsSeleccionadas = [];
                $('table tbody input[type="checkbox"]:checked').each(function() {
                    idsSeleccionadas.push($(this).val());

                    // Si se selecciona un checkbox, se agrega un input oculto al formulario
                    if (!document.getElementById('id_eliminar_' + $(this).val())) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'id_eliminar_' + $(this).val();
                        input.id = 'id_eliminar_' + $(this).val();
                        input.value = $(this).val();
                        document.getElementById('formulario_eliminar_multiple').appendChild(input);
                        // console.log('Se agregó el input: ' + input.name);
                    }
                });

                // en caso de que se deseleccione, se elimina el input oculto
                $('table tbody input[type="checkbox"]:not(:checked)').each(function() {
                    if (document.getElementById('id_eliminar_' + $(this).val())) {
                        document.getElementById('id_eliminar_' + $(this).val()).remove();
                        // console.log('Se eliminó el input: ' + 'id_eliminar_' + $(this).val());
                    }
                    // console.log('IDs seleccionadas: ' + idsSeleccionadas);
                });

                boton_eliminar = document.getElementById('btnEliminar');
                //console.log("botoneliminar: ", boton_eliminar);

                if (idsSeleccionadas.length === 0) { //Si está vacío el botón se inhabilita
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
                actualizaridsSeleccionadas();
            });
            checkbox.click(function() {
                if (!this.checked) {
                    $("#selectAll").prop("checked", false);
                }
                actualizaridsSeleccionadas();
            });

            // Actualizar el arreglo cuando se carga la página
            actualizaridsSeleccionadas();
        });
    </script>
@endsection

{{-- Script para validación de campos --}}
@section('script_validacion_campos')
    <script>
        document.querySelectorAll('.id').forEach(function(element) {
            element.addEventListener('input', function() {
                var valor = this.value;
                var valorLimpio = valor.replace(/[^A-Z0-9\-]+/g, '');
                valorLimpio = valorLimpio.substring(0, 13);
                this.value = valorLimpio;
            });
        });

        document.querySelectorAll('.name').forEach(function(element) {
            element.addEventListener('input', function() {
                var valor = this.value;

                // Sólo acepta Letras Mayúsculas y Minúsculas
                var valorLimpio = valor.replace(/[^a-zA-Z\sáéíóúÁÉÍÓÚ]+/g, '').replace(/\s{2,}/g, ' ');
                this.value = valorLimpio;
            });
        });
    </script>
@endsection

<!-- Se recibe la lista de columnas a mostrar (names) -->
@section('columnas')
    @if (!$usuarios->isEmpty())
        @foreach ($columnas as $columna)
            <th> {{ $columna }} </th>
        @endforeach

    @else
        @php
            echo "<script>
                document.getElementById('tabla_datos').style.display = 'none';
            </script>";
        @endphp
    @endif

    @php
        echo"<script>
            document.getElementById('btnAgregar').remove();
        </script>";
    @endphp
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

    @if ($usuarios->isEmpty())
        <p style="text-align: center;">No hay registros</p>
    @else
        @php
            // Asegúrate de que $usuarios sea un array para array_slice
            $usuarios_array = $usuarios->toArray();
            $segmento = 20; // Número de registros por página
            $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Página actual
            $inicio = ($pagina - 1) * $segmento; // Registro inicial de la página actual
            $usuarios_pagina = array_slice($usuarios_array, $inicio, $segmento); // Obtener los usuarios de la página actual
        @endphp

        @foreach ($usuarios_pagina as $usuario)
            <tr>
                <td>
                    <span class="custom-checkbox">
                        <input type="checkbox" class="checkbox" name="options[]" value="{{ $usuario['id'] }}">
                        <label></label>
                    </span>
                </td>
                <td class="id">{{ $usuario['id'] }}</td>
                <td class="name">{{ $usuario['name'] }}</td>
                <td class="email">{{ $usuario['email'] }}</td>
                <td class="email_verified_at">{{ $usuario['email_verified_at'] }}</td>
                <td class="rol">{{ $usuario['role_name'] }}</td> {{-- Muestra el nombre del rol --}}
                <td class="created_at">{{ $usuario['created_at'] }}</td>
                <td class="updated_at">{{ $usuario['updated_at'] }}</td>
                <td>
                    <a href="#editEmployeeModal" class="edit" data-toggle="modal"
                        data-id="{{ $usuario['id'] }}"
                        data-name="{{ $usuario['name'] }}"
                        data-role="{{ $usuario['role_name'] }}"> {{-- Pasar el rol actual al modal --}}
                        <i class="material-icons" data-toggle="tooltip" title="Editar">&#xE254;</i></a>
                    <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"
                        data-id="{{ $usuario['id'] }}"><i class="material-icons" data-toggle="tooltip"
                            title="Eliminar">&#xE872;</i></a>
                </td>
            </tr>
        @endforeach


        @php
            $totalPaginas = ceil(count($usuarios) / $segmento); // Total de páginas
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

{{-- Modulo editar --}}
@section('modulo_editar')

    @if ($usuarios)
        <form action="{{ route('usuarios.editar') }}" method="POST">
            {{-- Muy importante la directiva siguiente. --}}
            @csrf

            {{-- Aquí se debe guardar el ID del usuario a enviar al método del controlador para editar. --}}
            <input type="hidden" name="id_editar" id="id_editar">

            <div class="modal-header">
                <h4 class="modal-title">Editar @yield('administrar_s')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>ID</label>
                    {{-- El ID normalmente no se edita, por seguridad --}}
                    <input id="id_campo" type="text" class="form-control id ceditar" name="id" readonly required>
                </div>
                <div class="form-group">
                    <label>Nombre</label>
                    <input id="name_campo" type="text" class="form-control name ceditar" maxlength="75" name="name_mod" required >
                </div>
                {{-- Nuevo campo para el Rol --}}
                <div class="form-group">
                    <label>Rol</label>
                    <select id="role_campo" name="role_mod" class="form-control" required>
                        <option value="">Selecciona un rol</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}">{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Campo opcional para cambiar la contraseña --}}
                <div class="form-group">
                    <label>Nueva Contraseña (opcional)</label>
                    <input type="password" class="form-control" name="password_mod">
                    <small class="form-text text-muted">Deja este campo vacío para no cambiar la contraseña.</small>
                </div>
            </div>

            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                <input type="submit" class="btn btn-info" value="Guardar">
            </div>
        </form>

        {{-- Escucha que identifica según el atributo data-id el ID que se va a editar y llena el modal. --}}
        <script>
            $(document).ready(function() {
                $('a.edit').click(function() {
                    // Se obtienen los datos del registro a editar desde los atributos data-
                    var idEditar = $(this).data('id');
                    var name = $(this).data('name');
                    var role = $(this).data('role'); // Obtiene el rol del usuario

                    // Se llenan los campos del formulario con los datos del registro a editar
                    $('#id_editar').val(idEditar); // Campo oculto para el ID en el submit
                    $('#id_campo').val(idEditar); // Campo de texto visible del ID
                    $('#name_campo').val(name); // Campo de texto del nombre
                    $('#role_campo').val(role); // Selecciona el rol en el dropdown

                    // Los listeners para 'change' en los campos ya no son estrictamente necesarios
                    // para 'id_mod' y 'name_mod' si usas directamente los IDs de los campos de formulario
                    // en el submit del modal. Pero si los usas para algo más, se pueden mantener.
                    // Para simplificar, he dejado solo los campos que se pasan en el formulario.
                });

                // Vaciar los campos al dar clic en "Cancelar"
                $('input[type="button"][value="Cancelar"]').click(function() {
                    $('#id_campo').val('');
                    $('#name_campo').val('');
                    $('#role_campo').val(''); // Vaciar selección de rol
                });

                // Vaciar los campos al hacer submit del formulario (se limpian los campos visibles, no los ocultos)
                $('form').submit(function() {
                    // Puedes decidir si quieres limpiar los campos visibles después de un submit exitoso
                    // o si la recarga de la página se encarga de ello.
                });
            });
        </script>
        {{-- Aquí se incluye el script de validación de campos del layoutCrud --}}
        @yield('script_validacion_campos')

    @endif
@endsection


{{-- Modulo eliminar --}}
@section('modulo_eliminar')

    <form action="{{ route('usuarios.eliminar') }}" method="POST">
        {{-- Muy importante la directiva siguiente. --}}
        @csrf

        {{-- Aquí se debe guardar el numero de control a enviar al método del controlador para eliminar. --}}
        <input type="hidden" name="id_eliminar" id="id_eliminar">

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

    {{-- Escucha que identifica según el atributo data-id el no. control que se va a eliminar. --}}
    <script>
        $(document).ready(function() {
            $('a.delete').click(function() {
                var idEliminar = $(this).data('id');
                $('#id_eliminar').val(idEliminar);

                //console.log("No control:", id);
            });
        });
    </script>

@endsection


{{-- Modulo eliminar para multiples registros --}}
@section('modulo_eliminar_multiple')
    <form id="formulario_eliminar_multiple" action="{{ route('usuarios.eliminarMultiple') }}" method="POST">

        {{-- Muy importante la directiva siguiente. --}}
        @csrf

        <div class="modal-header">
            <h4 class="modal-title">Eliminar @yield('administrar_s')</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
            <p>¿Seguro que quiere eliminar estos registros?</p>
            <p class="text-warning"><small>Esta acción no se podrá deshacer.</small></p>
        </div>
        <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-danger" value="Eliminar">
        </div>
    </form>

@endsection
