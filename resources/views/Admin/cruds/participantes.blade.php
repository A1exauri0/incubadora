@include('Admin.cruds.layouts.header')

@extends('Admin.cruds.layouts.layoutParticipantes')

@section('titulo', $titulo)

@section('administrar', 'Participantes')

@section('administrar_s', 'Participante')

@section('datos')
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
    @endphp

    @if (!$hayAlumnos && !$hayAsesores && !$hayMentores)
        <tr>
            <td colspan="4" style="text-align: center;">No hay registros</td>
        </tr>
    @else
        @for ($i = 0; $i < $maxRows; $i++)
            <tr>
                <!-- COLUMNA DE ALUMNOS -->
                <td class="alumno_proyecto">
                    @if (isset($alumno_proyecto[$i]))
                        <!-- NOMBRE DEL ALUMNO -->
                        @foreach ($alumnos as $alumno)
                            @if ($alumno_proyecto[$i]->no_control === $alumno->no_control)
                                {{ $alumno->nombre }}
                            @endif
                        @endforeach
                        <!-- BOTON DE ELIMINAR -->
                        <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"
                            data-no-control="{{ $alumno_proyecto[$i]->no_control }}">
                            <i class="material-icons" data-toggle="tooltip" title="Eliminar">&#xE872;</i>
                        </a>
                    @else
                        <!-- Mostrar solo si no hay alumnos en toda la tabla -->
                        @if (!$hayAlumnos)
                            <span>No hay Alumnos registrados</span>
                        @endif
                    @endif
                </td>

                <!-- COLUMNA DE CARRERAS -->
                <td class="carrera_alumno_proyecto">
                    <!-- CARRERA DEL ALUMNO -->
                    @foreach ($alumnos as $alumno)
                        @if ($alumno_proyecto[$i]->no_control === $alumno->no_control)
                            {{ $alumno->carrera }}
                        @endif
                    @endforeach
                </td>

                <!-- COLUMNA DE ASESORES -->
                <td class="asesor_proyecto">
                    @if (isset($asesor_proyecto[$i]))
                        <!-- ID DEL ASESOR -->
                        @foreach ($asesores as $asesor)
                            @if ($asesor_proyecto[$i]->idAsesor === $asesor->idAsesor)
                                {{ $asesor->nombre }}
                            @endif
                        @endforeach
                        <!-- BOTON DE ELIMINAR -->
                        <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"
                            data-idasesor="{{ $asesor_proyecto[$i]->idAsesor }}">
                            <i class="material-icons" data-toggle="tooltip" title="Eliminar">&#xE872;</i>
                        </a>
                    @else
                        <!-- Mostrar solo si no hay asesores en toda la tabla -->
                        @if (!$hayAsesores)
                            <span>No hay Asesores registrados</span>
                        @endif
                    @endif
                </td>

                <!-- COLUMNA DE MENTORES -->
                <td class="mentor_proyecto">
                    @if (isset($mentor_proyecto[$i]))
                        <!-- ID DEL MENTOR -->
                        @foreach ($mentores as $mentor)
                            @if ($mentor_proyecto[$i]->idMentor === $mentor->idMentor)
                                {{ $mentor->nombre }}
                            @endif
                        @endforeach
                        <!-- BOTON DE ELIMINAR -->
                        <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"
                            data-idmentor="{{ $mentor_proyecto[$i]->idMentor }}">
                            <i class="material-icons" data-toggle="tooltip" title="Eliminar">&#xE872;</i>
                        </a>
                    @else
                        <!-- Mostrar solo si no hay mentores en toda la tabla -->
                        @if (!$hayMentores)
                            <span>No hay Mentores registrados</span>
                        @endif
                    @endif
                </td>
            </tr>
        @endfor
    @endif
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
                    url: '{{ route('participantes.buscar') }}',
                    type: 'GET',
                    data: {
                        'query': query,
                        'tipo': tipo
                    },
                    success: function(data) {
                        $('#participante_seleccionado').empty();
                        data.forEach(function(participante) {
                            var option = $('<option></option>')
                                .attr('value', participante.idAsesor || participante
                                    .no_control || participante.idMentor)
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

                $('#no_control_eliminar').val(noControlEliminar);
                $('#idAsesor_eliminar').val(idAsesorEliminar);
                $('#idMentor_eliminar').val(idMentorEliminar);

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
        <!-- Columna para la tabla -->
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

        <!-- Columna para el gráfico -->
        <div class="col-md-6">
            <canvas id="carrerasChart" width="400" height="400"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Obtener las etiquetas y datos
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
                labels: sortedLabels, // Carreras ordenadas
                datasets: [{
                    label: 'Número de Alumnos por Carrera',
                    data: sortedValues, // Total de alumnos por carrera
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
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
                    }
                }
            }
        });
    </script>

    <div class="modal-footer">
        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cerrar">
    </div>

@endsection
