@extends('layouts.layout')

@section('titulo', $titulo)

@section('content')
<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="display-5 text-dark mt-4">{{ $proyecto->nombre }}</h1>
            <p class="lead text-muted">Clave del proyecto: {{ $proyecto->clave_proyecto }}</p>
        </div>
        <div class="col-md-4 text-md-right">
            <a href="{{ route('home') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Inicio
            </a>
        </div>
    </div>

    {{-- Mostrar mensajes de éxito o error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        {{-- Tabla de Alumnos --}}
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Alumnos</h4>
                    {{-- Botón para agregar alumnos: solo admin o líder --}}
                    @if (Auth::user()->hasRole('admin') || ($esLider ?? false))
                        <a href="#" class="btn btn-sm btn-light" data-toggle="modal" data-target="#addAlumnoModal">
                            <i class="fas fa-plus"></i> Agregar
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No. Control</th>
                                    <th>Nombre</th>
                                    <th>Carrera</th>
                                    <th>Correo Institucional</th>
                                    <th>Líder</th>
                                    @if (Auth::user()->hasRole('admin') || ($esLider ?? false))
                                        <th style="width: 120px;">Acciones</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($alumnos_proyecto as $alumno)
                                <tr>
                                    <td>{{ $alumno->no_control }}</td>
                                    <td>{{ $alumno->nombre }}</td>
                                    <td>{{ $alumno->carrera }}</td>
                                    <td>{{ $alumno->correo_institucional }}</td>
                                    <td class="text-center">
                                        @if($alumno->lider)
                                            <i class="fas fa-crown text-warning" title="Líder"></i>
                                        @endif
                                    </td>
                                    @if (Auth::user()->hasRole('admin') || ($esLider ?? false))
                                    <td class="text-center">
                                        {{-- Botón para eliminar alumno --}}
                                        <button class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#deleteModal"
                                            data-id="{{ $alumno->no_control }}"
                                            data-tipo="alumno"
                                            data-proyecto="{{ $proyecto->clave_proyecto }}">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="@if (Auth::user()->hasRole('admin') || ($esLider ?? false)) 6 @else 5 @endif" class="text-center">No hay alumnos asignados a este proyecto.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de Asesores --}}
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Asesores</h4>
                    {{-- Botón para agregar asesores: solo admin o líder --}}
                    @if (Auth::user()->hasRole('admin') || ($esLider ?? false))
                        <a href="#" class="btn btn-sm btn-light" data-toggle="modal" data-target="#addAsesorModal">
                            <i class="fas fa-plus"></i> Agregar
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo Electrónico</th>
                                    @if (Auth::user()->hasRole('admin') || ($esLider ?? false))
                                        <th style="width: 120px;">Acciones</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($asesores_proyecto as $asesor)
                                <tr>
                                    <td>{{ $asesor->nombre }}</td>
                                    <td>{{ $asesor->correo_electronico }}</td>
                                    @if (Auth::user()->hasRole('admin') || ($esLider ?? false))
                                    <td class="text-center">
                                        {{-- Botón para eliminar asesor --}}
                                        <button class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#deleteModal"
                                            data-id="{{ $asesor->idAsesor }}"
                                            data-tipo="asesor"
                                            data-proyecto="{{ $proyecto->clave_proyecto }}">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="@if (Auth::user()->hasRole('admin') || ($esLider ?? false)) 3 @else 2 @endif" class="text-center">No hay asesores asignados a este proyecto.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PARA ELIMINAR PARTICIPANTE (ALUMNO/ASESOR) --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar a este participante del proyecto?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action=""> {{-- La acción se establecerá con JS --}}
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="no_control_eliminar" id="no_control_eliminar">
                    <input type="hidden" name="idAsesor_eliminar" id="idAsesor_eliminar">
                    <input type="hidden" name="clave_proyecto_eliminar" id="clave_proyecto_eliminar">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PARA AGREGAR ALUMNO --}}
<div class="modal fade" id="addAlumnoModal" tabindex="-1" role="dialog" aria-labelledby="addAlumnoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAlumnoModalLabel">Agregar Alumno al Proyecto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form id="searchAlumnoForm" class="form-inline justify-content-between mb-3">
                    <div class="form-group flex-grow-1 mr-2">
                        <label for="searchAlumnoInput" class="sr-only">Buscar Alumno por Número de Control, Nombre o Correo:</label>
                        <input type="text" class="form-control w-100" id="searchAlumnoInput" placeholder="Ej: 19130000, Juan Pérez o correo@ittec.edu.mx">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                </form>
                <hr class="my-4">
                <div id="alumnoSearchResults" class="mt-3">
                    <p class="text-muted text-center">Inicia una búsqueda para encontrar alumnos.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PARA AGREGAR ASESOR --}}
<div class="modal fade" id="addAsesorModal" tabindex="-1" role="dialog" aria-labelledby="addAsesorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAsesorModalLabel">Agregar Asesor al Proyecto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form id="searchAsesorForm" class="form-inline justify-content-between mb-3">
                    <div class="form-group flex-grow-1 mr-2">
                        <label for="searchAsesorInput" class="sr-only">Buscar Asesor por Nombre o Correo Electrónico:</label>
                        <input type="text" class="form-control w-100" id="searchAsesorInput" placeholder="Ej: Dra. García o asesor@ittec.edu.mx">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                </form>
                <hr class="my-4">
                <div id="asesorSearchResults" class="mt-3">
                    <p class="text-muted text-center">Inicia una búsqueda para encontrar asesores.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Script para animar los alerts
        setTimeout(function() {
            $(".alert").alert('close');
        }, 5000);

        // --- Lógica del Modal de ELIMINAR Participante ---
        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var tipo = button.data('tipo');
            var claveProyecto = button.data('proyecto');

            var modal = $(this);
            var deleteForm = modal.find('#deleteForm');

            // Limpiar todos los campos del modal para evitar que se envíen datos incorrectos
            modal.find('input[name="no_control_eliminar"]').val('');
            modal.find('input[name="idAsesor_eliminar"]').val('');
            modal.find('input[name="clave_proyecto_eliminar"]').val('');

            // Asignar el valor de la clave del proyecto
            modal.find('input[name="clave_proyecto_eliminar"]').val(claveProyecto);

            // Determinar la acción del formulario y asignar el identificador correcto
            if (tipo === 'alumno') {
                deleteForm.attr('action', '{{ route('participantes.alumno.eliminar') }}');
                modal.find('input[name="no_control_eliminar"]').val(id);
            } else if (tipo === 'asesor') {
                deleteForm.attr('action', '{{ route('participantes.asesor.eliminar') }}');
                modal.find('input[name="idAsesor_eliminar"]').val(id);
            }
        });


        // --- Lógica del Modal de AGREGAR ALUMNO ---
        $('#searchAlumnoForm').on('submit', function(e) {
            e.preventDefault();
            const searchTerm = $('#searchAlumnoInput').val();
            const searchResultsDiv = $('#alumnoSearchResults');
            searchResultsDiv.html('<p class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Buscando...</p>');

            $.ajax({
                url: '{{ route('api.alumnos.search') }}',
                method: 'GET',
                data: {
                    query: searchTerm,
                    _token: '{{ csrf_token() }}',
                    clave_proyecto: '{{ $proyecto->clave_proyecto }}'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    searchResultsDiv.empty();
                    if (response.alumnos.length > 0) {
                        let resultsHtml = '<ul class="list-group">';
                        response.alumnos.forEach(alumno => {
                            resultsHtml += `
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>${alumno.nombre} (${alumno.no_control} - ${alumno.correo_institucional})</span>
                                    <form action="{{ route('participantes.alumno.agregar') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="no_control_alumno" value="${alumno.no_control}">
                                        <input type="hidden" name="clave_proyecto" value="{{ $proyecto->clave_proyecto }}">
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Agregar</button>
                                    </form>
                                </li>
                            `;
                        });
                        resultsHtml += '</ul>';
                        searchResultsDiv.html(resultsHtml);
                    } else {
                        searchResultsDiv.html('<p class="text-center text-muted">No se encontraron alumnos.</p>');
                    }
                },
                error: function(xhr) {
                    console.error('Error al buscar alumnos:', xhr.responseText);
                    searchResultsDiv.html('<p class="text-center text-danger">Error al buscar alumnos.</p>');
                }
            });
        });

        // --- Lógica del Modal de AGREGAR ASESOR ---
        $('#searchAsesorForm').on('submit', function(e) {
            e.preventDefault();
            const searchTerm = $('#searchAsesorInput').val();
            const searchResultsDiv = $('#asesorSearchResults');
            searchResultsDiv.html('<p class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Buscando...</p>');

            $.ajax({
                url: '{{ route('api.asesores.search') }}',
                method: 'GET',
                data: {
                    query: searchTerm,
                    _token: '{{ csrf_token() }}',
                    clave_proyecto: '{{ $proyecto->clave_proyecto }}'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    searchResultsDiv.empty();
                    if (response.asesores.length > 0) {
                        let resultsHtml = '<ul class="list-group">';
                        response.asesores.forEach(asesor => {
                            let habilidadesHtml = '';
                            if (asesor.habilidades && asesor.habilidades.length > 0) {
                                habilidadesHtml = '<div class="habilidades-list mt-2">';
                                habilidadesHtml += '<p class="mb-1"><strong>Habilidades:</strong></p>';
                                asesor.habilidades.forEach(habilidad => {
                                    habilidadesHtml += `<span class="badge badge-primary mr-1 mb-1">${habilidad}</span>`;
                                });
                                habilidadesHtml += '</div>';
                            } else {
                                habilidadesHtml = '<p class="text-muted small mt-2 mb-0">Sin habilidades registradas.</p>';
                            }

                            resultsHtml += `
                                <li class="list-group-item d-flex flex-column align-items-start">
                                    <div class="d-flex justify-content-between w-100 mb-1">
                                        <span>${asesor.nombre} (${asesor.correo_electronico})</span>
                                        <form action="{{ route('participantes.asesor.agregar') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="id_asesor" value="${asesor.idAsesor}">
                                            <input type="hidden" name="clave_proyecto" value="{{ $proyecto->clave_proyecto }}">
                                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Agregar</button>
                                        </form>
                                    </div>
                                    ${habilidadesHtml}
                                </li>
                            `;
                        });
                        resultsHtml += '</ul>';
                        searchResultsDiv.html(resultsHtml);
                    } else {
                        searchResultsDiv.html('<p class="text-center text-muted">No se encontraron asesores.</p>');
                    }
                },
                error: function(xhr) {
                    console.error('Error al buscar asesores:', xhr.responseText);
                    searchResultsDiv.html('<p class="text-center text-danger">Error al buscar asesores.</p>');
                }
            });
        });
    });
</script>
<style>
    /* Estilos adicionales para las habilidades en los resultados del modal */
    .habilidades-list {
        display: flex;
        flex-wrap: wrap;
        gap: 5px; /* Espacio entre los badges */
    }
    .habilidades-list .badge {
        padding: 0.4em 0.6em;
        font-size: 0.85em;
        border-radius: 0.5rem;
        background-color: #007bff; /* Color primario de Bootstrap */
        color: white;
    }
    .modal-body .form-group label {
        font-weight: bold;
        color: #333;
    }
    .modal-body hr {
        border-top: 1px solid #dee2e6;
    }
    /* Estilo para el input de búsqueda en línea */
    .form-inline .form-group {
        flex: 1; /* Permite que el input crezca */
    }
    .form-inline .form-control {
        width: 100%; /* Asegura que el input ocupe el espacio disponible */
    }
    /* Ajuste para que el mensaje de "Sin habilidades" no tenga margen inferior excesivo */
    .habilidades-list .mb-0 {
        margin-bottom: 0 !important;
    }
</style>
@endpush