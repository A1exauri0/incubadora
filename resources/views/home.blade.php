@extends('layouts.layout')

@section('titulo', $titulo)

{{-- Módulo Simbología --}}
@section('modulo_simbologia')
    <div class="modal-header">
        <h4 class="modal-title">Simbología</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    </div>
    <div class="modal-body">
        <ul class="list-group mb-3">
            <p>Los colores de los proyectos indican su estado actual:</p>
            @foreach ($etapas as $etapa)
                <li class="list-group-item list-group-item-{{ $etapa->clase }}">
                    <strong>{{ $etapa->color }} ({{ $etapa->nombre }}):</strong> {{ $etapa->descripcion }}
                </li>
            @endforeach
        </ul>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    </div>
@endsection

{{-- Módulo Contenido --}}
@section('content')
    @hasanyrole('admin|alumno|asesor|mentor')
        <div class="container-fluid py-4">
            <div class="row mb-4 align-items-center">
                <div class="col-md-6">
                    <h1 class="display-5 text-dark">Mis Proyectos</h1>
                </div>
                <div class="col-md-6 text-md-right">
                    <button class="btn btn-info" data-toggle="modal" data-target="#simbologiaModal">
                        <i class="fas fa-info-circle"></i> Ver Simbología
                    </button>
                </div>
            </div>
            
            <div class="row">
                {{-- Panel de Proyectos --}}
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Proyectos Actuales</h4>
                        </div>
                        <div class="card-body p-3">
                            {{-- Campo de búsqueda --}}
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" id="searchInput" class="form-control" placeholder="Buscar proyecto...">
                            </div>
                        </div>
                        <div class="p-0" style="height: 500px; overflow-y: auto;">
                            <div class="list-group list-group-flush" id="proyectos-list">
                                @forelse ($proyectos as $proyecto)
                                    <a href="#" 
                                        class="list-group-item list-group-item-action list-group-item-{{ $proyecto->clase }}"
                                        data-clave="{{ $proyecto->clave_proyecto }}"
                                        data-nombre="{{ $proyecto->nombre }}"
                                        data-descripcion="{{ $proyecto->descripcion }}"
                                        data-categoria="{{ $proyecto->nombre_categoria }}"
                                        data-tipo="{{ $proyecto->nombre_tipo }}"
                                        data-etapa="{{ $proyecto->nombre_etapa }}"
                                        data-fecha="{{ $proyecto->fecha_agregado }}"
                                        data-video="{{ $proyecto->video }}"
                                        data-lider="{{ $proyecto->es_lider ?? 0 }}">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1 text-truncate">{{ $proyecto->nombre }}</h5>
                                            <small class="text-muted">{{ $proyecto->fecha_agregado }}</small>
                                        </div>
                                        <p class="mb-1 text-truncate">{{ $proyecto->nombre_etapa }}</p>
                                    </a>
                                @empty
                                    <div class="p-4 text-center" id="no-projects-message">
                                        <p class="mb-2">No participa en ningún proyecto.</p>
                                        @role('alumno')
                                            <a href="{{ route('proyectos.create') }}" class="btn btn-success btn-sm">
                                                Crear mi primer proyecto
                                            </a>
                                        @endrole
                                    </div>
                                @endforelse
                            </div>
                            <div class="p-4 text-center d-none" id="no-results-message">
                                <p class="mb-2">No se encontraron proyectos que coincidan con la búsqueda.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panel de Detalles del Proyecto --}}
                <div class="col-md-8">
                    <div id="detallesProyecto" class="card shadow-sm" style="display:none;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="card-title mb-0" id="detallesNombre"></h3>
                                <div class="ml-auto">
                                    <span class="badge badge-pill badge-primary" id="detallesEtapaBadge"></span>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="card-text"><strong>Clave:</strong> <span id="detallesClave"></span></p>
                                    <p class="card-text"><strong>Categoría:</strong> <span id="detallesCategoria"></span></p>
                                    <p class="card-text"><strong>Tipo:</strong> <span id="detallesTipo"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="card-text"><strong>Fecha de Registro:</strong> <span id="detallesFecha"></span></p>
                                </div>
                            </div>

                            <p class="card-text mt-3"><strong>Descripción:</strong> <span id="detallesDescripcion"></span></p>

                            <div id="detallesVideo" class="mt-4"></div>

                            {{-- Botones de acción --}}
                            <div class="mt-4 text-right">
                                <a href="#" id="botonEditarProyecto" class="btn btn-info btn-sm d-none">
                                    <i class="fas fa-edit"></i> Editar Proyecto
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Mensaje si no hay proyectos seleccionados --}}
                    @empty ($proyectos)
                    @else
                        <div id="no-proyecto-seleccionado" class="text-center p-5">
                            <i class="fas fa-arrow-circle-left fa-3x text-muted"></i>
                            <h4 class="mt-3 text-muted">Selecciona un proyecto de la lista para ver los detalles.</h4>
                        </div>
                    @endempty
                </div>
            </div>
        </div>
    @endhasanyrole
@endsection

@push('scripts')
<script>
    function mostrarDetalles(proyecto) {
        $('#detallesProyecto').show();
        $('#no-proyecto-seleccionado').hide();
        
        // Asignar los valores a los elementos del panel de detalles
        $('#detallesNombre').text(proyecto.nombre);
        $('#detallesClave').text(proyecto.clave);
        $('#detallesDescripcion').text(proyecto.descripcion);
        $('#detallesCategoria').text(proyecto.categoria);
        $('#detallesTipo').text(proyecto.tipo);
        $('#detallesFecha').text(proyecto.fecha);

        const badge = $('#detallesEtapaBadge');
        badge.text(proyecto.etapa);
        badge.removeClass().addClass(`badge badge-pill badge-${proyecto.clase}`);

        const videoContainer = $('#detallesVideo');
        if (proyecto.video) {
            const videoId = obtenerIdVideo(proyecto.video);
            if (videoId) {
                videoContainer.html(`<iframe width="100%" height="315" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`);
            } else {
                videoContainer.html('<p class="text-center text-muted">URL de video no válida.</p>');
            }
        } else {
            videoContainer.html('<p class="text-center text-muted">No hay video disponible.</p>');
        }

        const botonEditar = $('#botonEditarProyecto');
        if (proyecto.esLider == 1) {
            botonEditar.removeClass('d-none');
            botonEditar.attr('href', `/proyectos/${proyecto.clave}/editar`);
        } else {
            botonEditar.addClass('d-none');
        }
    }

    function obtenerIdVideo(url) {
        const regExp = /^.*(youtu.be\/|v\/|e\/|u\/\w+\/|embed\/|v=)([^#\&\?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }

    $(document).ready(function() {
        const projectItems = $('.list-group-item-action');
        const searchInput = $('#searchInput');
        const noResultsMessage = $('#no-results-message');
        const noProjectsMessage = $('#no-projects-message');
        
        // Función para filtrar los proyectos
        function filtrarProyectos() {
            const searchTerm = searchInput.val().toLowerCase();
            let hasResults = false;

            projectItems.each(function() {
                const item = $(this);
                const projectName = item.data('nombre').toLowerCase();
                if (projectName.includes(searchTerm)) {
                    item.show();
                    hasResults = true;
                } else {
                    item.hide();
                }
            });

            if (hasResults) {
                noResultsMessage.addClass('d-none');
            } else {
                noResultsMessage.removeClass('d-none');
            }
        }

        // Evento para el campo de búsqueda
        searchInput.on('input', function() {
            filtrarProyectos();
            // Lógica para seleccionar el primer resultado visible
            const firstVisibleProject = $('.list-group-item-action:visible').first();
            if (firstVisibleProject.length) {
                projectItems.removeClass('active');
                firstVisibleProject.addClass('active');
                firstVisibleProject.trigger('click'); // Simula un clic en el primer elemento
            } else {
                $('#detallesProyecto').hide();
                $('#no-proyecto-seleccionado').hide();
            }
        });
        
        // Evento para el clic en los proyectos
        projectItems.on('click', function(event) {
            event.preventDefault();
            
            projectItems.removeClass('active');
            $(this).addClass('active');

            const proyectoData = {
                clave: $(this).data('clave'),
                nombre: $(this).data('nombre'),
                descripcion: $(this).data('descripcion'),
                categoria: $(this).data('categoria'),
                tipo: $(this).data('tipo'),
                etapa: $(this).data('etapa'),
                clase: $(this).attr('class').split(' ').find(cls => cls.startsWith('list-group-item-')).replace('list-group-item-', ''),
                fecha: $(this).data('fecha'),
                video: $(this).data('video'),
                esLider: $(this).data('lider')
            };

            mostrarDetalles(proyectoData);
        });

        // Carga los detalles del primer proyecto al iniciar la página
        const firstProject = projectItems.first();
        if (firstProject.length) {
            firstProject.trigger('click');
        } else if (noProjectsMessage.length) {
            noProjectsMessage.show();
        }
    });
</script>
@endpush