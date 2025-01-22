@include('Admin.cruds.layouts.header')

<!-- Se extiende de la clase layout en CRUDs (solamente para CRUDs) -->
@extends('Admin.cruds.layouts.layout')

<!-- Se recibe la variable del título que tendrá la página en la pestaña -->
@section('titulo', $titulo)

<!-- ¿Qué se va a administrar en plural? -->
@section('administrar', 'Tokens')

<!-- ¿Qué se va a administrar en singular? -->
@section('administrar_s', 'Token')

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
                    if (!document.getElementById('idToken_eliminar_' + $(this).val())) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'idToken_eliminar_' + $(this).val();
                        input.id = 'idToken_eliminar_' + $(this).val();
                        input.value = $(this).val();
                        document.getElementById('formulario_eliminar_multiple').appendChild(input);
                        // console.log('Se agregó el input: ' + input.name);
                    }

                });

                // en caso de que se deseleccione, se elimina el input oculto
                $('table tbody input[type="checkbox"]:not(:checked)').each(function() {
                    if (document.getElementById('idToken_eliminar_' + $(this).val())) {
                        document.getElementById('idToken_eliminar_' + $(this).val()).remove();
                        // console.log('Se eliminó el input: ' + 'idToken_eliminar_' + $(this).val());
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
        document.querySelectorAll('.idToken').forEach(function(element) {
            element.addEventListener('input', function() {
                var valor = this.value;
                var valorNumerico = valor.replace(/\D/g, '');
                //No permitir más de 8 dígitos
                valorNumerico = valorNumerico.substring(0, 8);
                this.value = valorNumerico;
            });
        });
    </script>
@endsection

<!-- Se recibe la lista de columnas a mostrar (nombres) -->
@section('columnas')

    @if (!$tokens->isEmpty())
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

    @if ($tokens->isEmpty())
        <p style="text-align: center;">No hay registros</p>
    @else
        @php
            $tokens = $tokens->toArray(); // Convert $tokens to an array
            $segmento = 20; // Número de registros por página
            $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Página actual
            $inicio = ($pagina - 1) * $segmento; // Registro inicial de la página actual
            $tokens_pagina = array_slice($tokens, $inicio, $segmento); // Obtener los tokens de la página actual
        @endphp

        @foreach ($tokens_pagina as $token)
            <tr>
                <td>
                    <span class="custom-checkbox">
                        <input type="checkbox" class="checkbox" name="options[]" value="{{ $token->idToken }}">
                        <label></label>
                    </span>
                </td>
                <td class="idToken">{{ $token->idToken }}</td>
                <td class="token">{{ $token->token }}</td>
                <td class="rol">{{  $roles->firstWhere('id', $token->rol)->name ?? 'N/A'; }}</td>
                <td class="correo">{{ $token->correo }}</td>
                <td>
                    <a href="#editEmployeeModal" class="edit" data-toggle="modal"
                        data-idtoken="{{ $token->idToken }}" data-token="{{ $token->token }}" data-rol="{{ $token->rol }}" data-correo="{{ $token->correo }}">
                        <i class="material-icons" data-toggle="tooltip" title="Editar">&#xE254;</i></a>
                    <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"
                        data-idtoken="{{ $token->idToken }}"><i class="material-icons" data-toggle="tooltip"
                            title="Eliminar">&#xE872;</i></a>
                </td>
            </tr>
        @endforeach


        @php
            $totalPaginas = ceil(count($tokens) / $segmento); // Total de páginas
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

    <form action="{{ route('tokens.agregar') }}" method="POST">
        {{-- Muy importante la directiva siguiente. --}}
        @csrf

        <div class="modal-header">
            <h4 class="modal-title">Agregar @yield('administrar_s')</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label>Correo</label>
                <input id="correo_agregar" name="correo_agregar" type="email" class="form-control correo" value="" maxlength="50" required>
            </div>

            <div class="form-group">
                <label>Rol</label>
                <select name="rol_agregar" class="form-control" required>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->name }}" class="form-control"> {{ $rol->name }} </option>
                    @endforeach
                </select>
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
                $('#correo_agregar').val('');
                $('select[name="rol_agregar"]').prop('selectedIndex', -1);
            });
        });
    </script>

    @yield('script_validacion_campos')

@endsection

{{-- Modulo editar --}}
@section('modulo_editar')

    @if ($tokens)
        <form action="{{ route('tokens.editar') }}" method="POST">
            {{-- Muy importante la directiva siguiente. --}}
            @csrf

            {{-- Aquí se debe guardar el numero de control a enviar al método del controlador para eliminar. --}}
            <input type="hidden" name="idToken_editar" id="idToken_editar">
            <input type="hidden" name="correo_mod" id="correo_mod">
            <input type="hidden" name="rol_mod" id="rol_mod">

            <div class="modal-header">
                <h4 class="modal-title">Editar @yield('administrar_s')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Correo</label>
                    <input id="correo_campo" type="email" class="form-control correo ceditar" value="" maxlength="50" required>
                </div>

                <div class="form-group">
                    <label>Rol</label>
                    <select id="rol_campo" name="rol_campo" class="form-control rol ceditar" required>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->name }}" class="form-control"
                                {{ isset($token) && $rol->id == $token->rol ? 'selected' : '' }}> {{ $rol->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                <input type="submit" class="btn btn-info" value="Guardar">
            </div>
        </form>

        {{-- Escucha que identifica según el atributo data-idToken el no. control que se va a editar. --}}
        <script>
            // Se ejecuta cuando el documento esté listo
            $(document).ready(function() {
                $('a.edit').click(function() {
                    // Se obtienen los datos del registro a editar
                    var idToken = $(this).data('idtoken');
                    var correo = $(this).data('correo');
                    var rolId = $(this).data('rol');
                    var roles = @json($roles);
                    var rol = roles.find(role => role.id == rolId).name;
                    

                    // Se llenan los campos del formulario con los datos del registro a editar
                    $('.correo').val(correo);
                    $('.form-control option').removeAttr('selected');
                    $('.form-control option[value="' + rol + '"]').attr('selected', 'selected');

                    // Se llenan los campos ocultos con los datos del registro a editar
                    $('#idToken_editar').val(idToken);
                    $('#correo_mod').val(correo);
                    $('#rol_mod').val(rol);

                    //Listeners para cuando cambien los campos
                    document.getElementById('correo_campo').addEventListener('change', function() {
                        $('#correo_mod').val(this.value);
                    });
                    
                    document.getElementById('rol_campo').addEventListener('change', function() {
                        $('#rol_mod').val(this.value);
                    });

                    console.log("rol:", rol);
                });

            });

            // Vaciar los campos al dar clic en "Cancelar"
            $('input[type="button"]').click(function() {
                $('.correo').val('');
                $('.rol').prop('selectedIndex', -1);
            });


            // Vaciar los campos al hacer submit del formulario
            $('form').submit(function() {
                $('.correo.ceditar').val('');
                $('.rol.ceditar').prop('selectedIndex', -1);
            });
        </script>

        @yield('script_validacion_campos')

    @endif
@endsection


{{-- Modulo eliminar --}}
@section('modulo_eliminar')

    <form action="{{ route('tokens.eliminar') }}" method="POST">
        {{-- Muy importante la directiva siguiente. --}}
        @csrf

        {{-- Aquí se debe guardar el numero de control a enviar al método del controlador para eliminar. --}}
        <input type="hidden" name="idToken_eliminar" id="idToken_eliminar">

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

    {{-- Escucha que identifica según el atributo data-idToken el no. control que se va a eliminar. --}}
    <script>
        $(document).ready(function() {
            $('a.delete').click(function() {
                var noControlEliminar = $(this).data('idtoken');
                $('#idToken_eliminar').val(noControlEliminar);

                //console.log("No control:", noControlEliminar);
            });
        });
    </script>

@endsection


{{-- Modulo eliminar para multiples registros --}}
@section('modulo_eliminar_multiple')
    <form id="formulario_eliminar_multiple" action="{{ route('tokens.eliminarMultiple') }}" method="POST">

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