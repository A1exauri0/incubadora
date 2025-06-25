<!-- Se extiende de la clase layout en CRUDs (solamente para CRUDs) -->
@extends('layouts.layoutParticipantes')

{{-- Título de la página --}}
@section('titulo', $titulo)

@section('administrar', 'Participantes')
@section('administrar_s', 'Participante')

{{-- Todas las columnas para el encabezado de la tabla (incluyendo Líder como la primera) --}}
@section('columnas')

@endsection

@section('datos')
    <style>
        /* Estilos para el botón de corona y el SVG */
        .crown-button {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            display: inline-flex;
            /* Usar flexbox para alinear el SVG */
            align-items: center;
            /* Centrar verticalmente */
            vertical-align: middle;
            /* Alinea el botón con el contenido de la celda */
        }

        .crown-icon {
            width: 22px;
            /* Tamaño un poco más grande */
            height: 22px;
            /* Tamaño un poco más grande */
            transition: fill 0.2s ease-in-out, filter 0.2s ease-in-out;
        }

        /* Estado inicial: gris claro para no líder */
        .crown-button .crown-icon[data-is-leader="0"] {
            fill: #cccccc;
        }

        /* Estado al pasar el cursor (hover) para no líder */
        .crown-button:hover .crown-icon[data-is-leader="0"] {
            fill: #888888;
            /* Gris más oscuro al pasar el cursor */
        }

        /* Estado activo: dorado para el líder */
        .crown-button .crown-icon[data-is-leader="1"] {
            fill: #FFD700;
            /* Dorado para el líder activo */
            filter: drop-shadow(0px 0px 3px rgba(255, 215, 0, 0.7));
            /* Sombra para resaltar */
        }

        /* Eliminar cursor de pointer si ya es líder */
        .crown-button[data-is-leader="1"] {
            cursor: default;
        }
    </style>
    @if (session('error'))
        <div id="error-message" style="text-align: center; background-color: rgb(155, 38, 38); color: white;">
            <p style="font-size: 15px">{{ session('error') }}</p>
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('error-message').style.display = 'none';
            }, 10000);
        </script>
    @endif

    @php
        $hayAlumnos = $alumno_proyecto->isNotEmpty();
        $hayAsesores = $asesor_proyecto->isNotEmpty();
        $hayMentores = $mentor_proyecto->isNotEmpty();

        // Determinar el número máximo de filas necesarias
        $maxRows = max($alumno_proyecto->count(), $asesor_proyecto->count(), $mentor_proyecto->count());

        // Encontrar al líder actual del equipo para pasarlo a JS
        $currentLeaderNoControl = null;
        foreach ($alumno_proyecto as $alumno) {
            if ($alumno->lider == 1) {
                $currentLeaderNoControl = $alumno->no_control;
                break;
            }
        }

        // Variables para controlar si ya se mostraron los mensajes de "no hay"
        $mensajeAlumnosMostrado = false;
        $mensajeAsesoresMostrado = false;
        $mensajeMentoresMostrado = false;
    @endphp

    @if (!$hayAlumnos && !$hayAsesores && !$hayMentores)
        <tr>
            {{-- Colspan ajustado para las 5 columnas: Líder, Alumnos, Carrera, Asesores, Mentores --}}
            <td colspan="5" style="text-align: center;">No hay registros</td>
        </tr>
    @else
        @for ($i = 0; $i < $maxRows; $i++)
            <tr>
                {{-- Primera columna para el botón de líder --}}
                <td>
                    @if (isset($alumno_proyecto[$i]))
                        {{-- Asegúrate de que $alumno esté definido antes de intentar acceder a su propiedad nombre --}}
                        @php
                            $nombreAlumnoParaBoton = '';
                            foreach ($alumnos as $alumno) {
                                if ($alumno_proyecto[$i]->no_control === $alumno->no_control) {
                                    $nombreAlumnoParaBoton = $alumno->nombre;
                                    break;
                                }
                            }
                        @endphp
                        <button class="crown-button" data-no-control="{{ $alumno_proyecto[$i]->no_control }}"
                            data-clave-proyecto="{{ $alumno_proyecto[$i]->clave_proyecto }}"
                            data-is-leader="{{ $alumno_proyecto[$i]->lider }}"
                            data-name="{{ $nombreAlumnoParaBoton }}" {{-- **Añadido: Pasar el nombre del alumno** --}}
                            title="Designar como líder de equipo">
                            <svg class="crown-icon" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 576 512" data-is-leader="{{ $alumno_proyecto[$i]->lider }}">
                                <path
                                    d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z" />
                            </svg>
                        </button>
                    @endif
                </td>

                <td class="alumno_proyecto">
                    {{-- Contenedor flex para alinear nombre y botón de eliminar --}}
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        @if (isset($alumno_proyecto[$i]))
                            <div>
                                @foreach ($alumnos as $alumno)
                                    @if ($alumno_proyecto[$i]->no_control === $alumno->no_control)
                                        {{ $alumno->nombre }}
                                    @endif
                                @endforeach
                            </div>
                            <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"
                                data-no-control="{{ $alumno_proyecto[$i]->no_control }}"
                                data-clave-proyecto="{{ $alumno_proyecto[$i]->clave_proyecto }}">
                                <i class="material-icons" data-toggle="tooltip" title="Eliminar">&#xE872;</i>
                            </a>
                        @else
                            @if (!$hayAlumnos && !$mensajeAlumnosMostrado)
                                <span>No hay Alumnos registrados</span>
                                @php $mensajeAlumnosMostrado = true; @endphp
                            @endif
                        @endif
                    </div>
                </td>

                <td class="carrera_alumno_proyecto">
                    @if (isset($alumno_proyecto[$i]))
                        @foreach ($alumnos as $alumno)
                            @if (isset($alumno_proyecto[$i]) && $alumno_proyecto[$i]->no_control === $alumno->no_control)
                                {{ $alumno->carrera }}
                            @endif
                        @endforeach
                    @endif
                </td>

                <td class="asesor_proyecto">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        @if (isset($asesor_proyecto[$i]))
                            <div>
                                @foreach ($asesores as $asesor)
                                    @if ($asesor_proyecto[$i]->idAsesor === $asesor->idAsesor)
                                        {{ $asesor->nombre }}
                                    @endif
                                @endforeach
                            </div>
                            <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"
                                data-idasesor="{{ $asesor_proyecto[$i]->idAsesor }}"
                                data-clave-proyecto="{{ $asesor_proyecto[$i]->clave_proyecto }}">
                                <i class="material-icons" data-toggle="tooltip" title="Eliminar">&#xE872;</i>
                            </a>
                        @else
                            @if (!$hayAsesores && !$mensajeAsesoresMostrado)
                                <span>No hay Asesores registrados</span>
                                @php $mensajeAsesoresMostrado = true; @endphp
                            @endif
                        @endif
                    </div>
                </td>

                <td class="mentor_proyecto">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        @if (isset($mentor_proyecto[$i]))
                            <div>
                                @foreach ($mentores as $mentor)
                                    @if ($mentor_proyecto[$i]->idMentor === $mentor->idMentor)
                                        {{ $mentor->nombre }}
                                    @endif
                                @endforeach
                            </div>
                            <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"
                                data-idmentor="{{ $mentor_proyecto[$i]->idMentor }}"
                                data-clave-proyecto="{{ $mentor_proyecto[$i]->clave_proyecto }}">
                                <i class="material-icons" data-toggle="tooltip" title="Eliminar">&#xE872;</i>
                            </a>
                        @else
                            @if (!$hayMentores && !$mensajeMentoresMostrado)
                                <span>No hay Mentores registrados</span>
                                @php $mensajeMentoresMostrado = true; @endphp
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
        @endfor
    @endif

    <script>
        // Pasar la clave del proyecto actual y el no_control del líder actual a JavaScript
        var currentClaveProyecto = "{{ $clave_proyecto }}";
        var currentLeaderNoControl = "{{ $currentLeaderNoControl }}";

        $(document).ready(function() {
            // Inicializar el estado visual de las coronas
            $('.crown-button').each(function() {
                var isLeader = $(this).data('is-leader');
                var svgIcon = $(this).find('.crown-icon');
                if (isLeader == 1) {
                    // Estado de líder activo
                    svgIcon.css('fill', '#FFD700');
                    svgIcon.css('filter', 'drop-shadow(0px 0px 3px rgba(255, 215, 0, 0.7))');
                    $(this).css('cursor', 'default'); // Si ya es líder, no hay cursor de puntero
                } else {
                    // Estado de no líder
                    svgIcon.css('fill', '#cccccc');
                    svgIcon.css('filter', 'none');
                    $(this).css('cursor', 'pointer'); // Cursor de puntero para seleccionar
                }
            });

            // Hover effect for crown buttons (only for non-leaders)
            $('.crown-button').hover(
                function() {
                    var isLeader = $(this).data('is-leader');
                    var svgIcon = $(this).find('.crown-icon');
                    if (isLeader != 1) { // Only change on hover if not already leader
                        svgIcon.css('fill', '#888888'); // Un poco más oscuro al pasar el cursor
                    }
                },
                function() {
                    var isLeader = $(this).data('is-leader');
                    var svgIcon = $(this).find('.crown-icon');
                    if (isLeader != 1) { // Revert if not leader
                        svgIcon.css('fill', '#cccccc'); // Volver a gris claro
                    }
                }
            );

            // Click event for crown buttons
            $('.crown-button').on('click', function() {
                var clickedNoControl = $(this).data('no-control');
                var clickedClaveProyecto = $(this).data('clave-proyecto');
                var isClickedLeader = $(this).data('is-leader');
                var clickedName = $(this).data('name'); // Obtener el nombre del alumno clicado

                if (isClickedLeader == 1) {
                    // Si ya es el líder, no hacer nada
                    return;
                }

                var modalTitle = '';
                var modalBodyText = '';
                var modalWarningText = '';

                // Determinar el mensaje del modal basado en si ya hay un líder
                if (currentLeaderNoControl) {
                    // Ya existe un líder, se preguntará si se desea cambiarlo
                    modalTitle = 'Cambiar Líder de Equipo';
                    modalBodyText = `Ya existe un líder para este equipo. ¿Deseas cambiar el líder actual por ${clickedName}?`;
                    modalWarningText = 'El líder anterior dejará de serlo.';
                } else {
                    // No hay líder actual, se preguntará si se desea designarlo
                    modalTitle = 'Designar Líder de Equipo';
                    modalBodyText = `¿Deseas designar a ${clickedName} como líder de este equipo?`;
                    modalWarningText = ''; // No hay advertencia de cambio si no hay líder
                }

                // Actualizar el contenido del modal
                $('#changeLeaderModal .modal-title').text(modalTitle);
                $('#changeLeaderModal .modal-body p:first').text(modalBodyText);
                // Si existe el segundo párrafo (para la advertencia), actualizarlo, si no, crearlo o manejarlo.
                // Aseguramos que el <small> siempre esté dentro de un <p> con la clase text-warning
                if (modalWarningText) {
                    $('#changeLeaderModal .modal-body p.text-warning small').text(modalWarningText).parent().show();
                } else {
                    $('#changeLeaderModal .modal-body p.text-warning').hide(); // Ocultar si no hay advertencia
                }


                // Almacenar los datos del nuevo líder potencial para el botón de confirmación del modal
                $('#confirmChangeLeaderBtn').data('new-leader-no-control', clickedNoControl);
                $('#confirmChangeLeaderBtn').data('clave-proyecto', clickedClaveProyecto);

                // Mostrar el modal de confirmación
                $('#changeLeaderModal').modal('show');
            });

            // Evento click para el botón "Cambiar" dentro del modal de confirmación
            $('#confirmChangeLeaderBtn').on('click', function() {
                var newLeaderNoControl = $(this).data('new-leader-no-control');
                var claveProyecto = $(this).data('clave-proyecto');

                // Ocultar el modal de confirmación
                $('#changeLeaderModal').modal('hide');

                // Realizar la petición AJAX para actualizar el líder
                $.ajax({
                    url: "{{ route('participantes.actualizarLider') }}", // Asegúrate de definir esta ruta en Laravel
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Token CSRF para seguridad
                        no_control_nuevo_lider: newLeaderNoControl,
                        clave_proyecto: claveProyecto
                    },
                    success: function(response) {
                        if (response.success) {
                            // Actualizar el estado visual de las coronas en la tabla
                            $('.crown-button').each(function() {
                                var btnNoControl = $(this).data('no-control');
                                var svgIcon = $(this).find('.crown-icon');

                                if (btnNoControl === newLeaderNoControl) {
                                    // Nuevo líder: color dorado, sombra y data-is-leader a 1
                                    svgIcon.css('fill', '#FFD700');
                                    svgIcon.css('filter',
                                        'drop-shadow(0px 0px 3px rgba(255, 215, 0, 0.7))'
                                    );
                                    $(this).data('is-leader', 1);
                                    $(this).css('cursor', 'default');
                                    currentLeaderNoControl =
                                        newLeaderNoControl; // Actualizar la variable global del líder
                                } else {
                                    // Otros alumnos del mismo proyecto: color gris, sin sombra y data-is-leader a 0
                                    if ($(this).data('clave-proyecto') ===
                                        claveProyecto) {
                                        svgIcon.css('fill', '#cccccc');
                                        svgIcon.css('filter', 'none');
                                        $(this).data('is-leader', 0);
                                        $(this).css('cursor', 'pointer');
                                    }
                                }
                            });
                            // Opcional: Mostrar un mensaje de éxito al usuario
                            // alert("Líder de equipo actualizado con éxito."); // Considera usar un modal o div para mensajes
                        } else {
                            // Manejar el error
                            // alert("Error al actualizar el líder: " + (response.message || "")); // Considera usar un modal o div para mensajes
                            console.error("Error al actualizar el líder:", response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error, xhr.responseText);
                        // alert("Ocurrió un error en la comunicación con el servidor."); // Considera usar un modal o div para mensajes
                    }
                });
            });
        });
    </script>
@endsection

@section('modulo_agregar')
    <form action="{{ route('participantes.agregar') }}" method="POST">
        @csrf
        <input type="hidden" name="clave_proyecto_agregar" value="{{ $clave_proyecto }}">
        @if ($clave_proyecto == 'vacio')
            {{ $clave_proyecto = null }}
        @endif
        <input type="hidden" name="tipo_agregar" id="tipoParticipanteInput" value="alumno">
        <input type="hidden" name="id_agregar" id="participanteSeleccionadoInput">

        <div class="modal-header">
            <h4 class="modal-title">Agregar @yield('administrar_s')</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label>Escoge el Tipo de Participante:</label>
                <select id="tipoParticipante" class="form-control">
                    <option value="alumno">Alumno</option>
                    <option value="asesor">Asesor</option>
                    <option value="mentor">Mentor</option>
                </select>
                <label for="buscarParticipante" class="mt-2">Buscar Participante:</label>
                <input type="text" id="buscarParticipante" class="form-control" placeholder="Escribe para buscar..."
                    autocomplete="off">
                <select name="participante_seleccionado" id="participante_seleccionado" class="form-control mt-2"
                    size="5">
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
            $('#buscarParticipante').on('keyup', function() {
                var query = $(this).val();
                var tipo = $('#tipoParticipante').val();

                $.ajax({
                    url: "{{ route('participantes.buscar') }}",
                    type: 'GET',
                    data: {
                        'query': query,
                        'tipo': tipo
                    },
                    success: function(data) {
                        $('#participante_seleccionado').empty(); // Limpia el dropdown.
                        data.forEach(function(participante) {
                            var proyectos = participante.proyectos ? participante
                                .proyectos.split(',').join(', ') : 'Sin proyectos';

                            var option = $('<option></option>')
                                .attr('value', participante.id)
                                .attr('title', 'Proyectos en los que participa: ' +
                                    proyectos) // Tooltip con los proyectos.
                                .text(participante.nombre);

                            $('#participante_seleccionado').append(option);
                        });
                    }
                });
            });

            $('#tipoParticipante').on('change', function() {
                $('#buscarParticipante').val('');
                $('#participante_seleccionado').empty();
                $('#tipoParticipanteInput').val($(this).val());
            });

            $('#participante_seleccionado').on('change', function() {
                $('#participanteSeleccionadoInput').val($(this).val());
            });
        });
    </script>

@endsection

{{-- Modulo eliminar --}}
@section('modulo_eliminar')

    <form action="{{ route('participantes.eliminar') }}" method="POST">
        {{-- Muy importante la directiva siguiente. --}}
        @csrf

        {{-- Aquí se debe guardar el id a enviar al método del controlador para eliminar. --}}
        <input type="hidden" name="no_control_eliminar" id="no_control_eliminar">
        <input type="hidden" name="idAsesor_eliminar" id="idAsesor_eliminar">
        <input type="hidden" name="idMentor_eliminar" id="idMentor_eliminar">
        <input type="hidden" name="clave_proyecto_eliminar" id="clave_proyecto_eliminar">

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
                var idAsesorEliminar = $(this).data('idasesor');
                var idMentorEliminar = $(this).data('idmentor');
                var claveProyectoEliminar = $(this).data('clave-proyecto');

                $('#no_control_eliminar').val(noControlEliminar);
                $('#idAsesor_eliminar').val(idAsesorEliminar);
                $('#idMentor_eliminar').val(idMentorEliminar);
                $('#clave_proyecto_eliminar').val(claveProyectoEliminar);

                //console.log("No control:", noControl);
            });
        });
    </script>

@endsection

@section('modulo_estadisticas')

    <div class="modal-header">
        <h4 class="modal-title">Gráfica de carreras de los @yield('administrar')</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    </div>

    <div class="modal-body row">
        <div class="col-md-6">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Carrera</th>
                        <th>Total de Alumnos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estadisticas as $estadistica)
                        <tr>
                            <td>{{ $estadistica->carrera }}</td>
                            <td>{{ $estadistica->total_alumnos }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <canvas id="carrerasChart" width="400" height="400"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener etiquetas y datos
            var labels = @json($estadisticas->pluck('carrera'));
            var data = @json($estadisticas->pluck('total_alumnos'));

            // Ordenar los datos de mayor a menor
            var sortedData = labels.map((label, index) => {
                return {
                    label: label,
                    value: data[index]
                };
            }).sort((a, b) => b.value - a.value);

            // Extraer etiquetas y valores ordenados
            var sortedLabels = sortedData.map(item => item.label);
            var sortedValues = sortedData.map(item => item.value);

            // Crear el gráfico de pastel
            var ctx = document.getElementById('carrerasChart').getContext('2d');
            var carrerasChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: sortedLabels,
                    datasets: [{
                        label: 'Número de Alumnos por Carrera',
                        data: sortedValues,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        datalabels: {
                            formatter: (value, context) => {
                                let total = context.chart.data.datasets[0].data.reduce((acc, cur) =>
                                    acc + cur, 0);
                                let percentage = (value / total * 100).toFixed(2);
                                return `${percentage}%`;
                            },
                            color: '#000',
                            font: {
                                weight: 'bold',
                                size: 12
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        });
    </script>

    <div class="modal-footer">
        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cerrar">
    </div>

@endsection

{{-- Nuevo módulo para el modal de confirmación de cambio de líder --}}
@section('modulo_cambiar_lider')
    <div id="changeLeaderModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- El título se actualizará dinámicamente con JS --}}
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    {{-- El primer párrafo se actualizará dinámicamente con JS --}}
                    <p></p>
                    {{-- Este párrafo con la advertencia se mostrará/ocultará/actualizará con JS --}}
                    <p class="text-warning"><small></small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmChangeLeaderBtn">Cambiar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
