@extends('layouts.layout')

@section('titulo', $titulo)

{{-- Modulo simbologia --}}
@section('modulo_simbologia')
    <div class="modal-header">
        <h4 class="modal-title">Simbología</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    </div>
    <div class="modal-body">
        <ul class="list-group mb-3" id="contenido">
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

{{-- Modulo contenido --}}
@section('content')
    @hasanyrole('admin|alumno|asesor|mentor')
        <div class="container-fluid">
            <h1 style="text-align: center;">Proyectos</h1>
            <div class="row">
                <div class="col-md-4">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#simbologiaModal">
                        Ver Simbología
                    </button>

                    <BR></BR>
                    <div style="height: 500px; overflow-y: auto;">
                        <ul class="list-group">
                            @forelse ($proyectos as $proyecto)
                                <li class="list-group-item list-group-item-{{ $proyecto->clase }}"
                                    onclick="mostrarDetalles(
                                        '{{ $proyecto->clave_proyecto }}',
                                        '{{ $proyecto->nombre }}',
                                        '{{ $proyecto->descripcion }}',
                                        '{{ $proyecto->nombre_categoria }}', {{-- Usar el nombre --}}
                                        '{{ $proyecto->nombre_tipo }}',      {{-- Usar el nombre --}}
                                        '{{ $proyecto->nombre_etapa }}',     {{-- Usar el nombre --}}
                                        '{{ $proyecto->fecha_agregado }}',
                                        '{{ $proyecto->video }}',
                                        '{{ $proyecto->es_lider ?? 0 }}'
                                    )">
                                    <strong>Nombre:</strong> {{ $proyecto->nombre }}<br>
                                    <strong>Fecha de registro:</strong> {{ $proyecto->fecha_agregado }}
                                </li>
                            @empty
                                <li class="list-group-item">No participa en ningún proyecto.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="col-md-8">
                    <div id="detallesProyecto" style="display:none;">
                        <div class="card">
                            <div class="card-body">
                                {{-- Contenedor para el título y el botón --}}
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0" id="detallesNombre"></h5>
                                    {{-- Botón de Editar Proyecto (inicialmente oculto) --}}
                                    <a href="#" id="botonEditarProyecto" class="btn btn-info btn-sm" style="display: none;">
                                        Editar Proyecto
                                    </a>
                                </div>

                                <p class="card-text"><strong>Descripción:</strong> <span id="detallesDescripcion"></span></p>
                                <p class="card-text"><strong>Categoría:</strong> <span id="detallesCategoria"></span></p>
                                <p class="card-text"><strong>Tipo:</strong> <span id="detallesTipo"></span></p>
                                <p class="card-text"><strong>Etapa:</strong> <span id="detallesEtapa"></span></p>
                                <p class="card-text"><strong>Fecha de registro:</strong> <span id="detallesFecha"></span></p>
                                <div id="detallesVideo" style="margin-top: 20px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endhasanyrole

    <script>
        function mostrarDetalles(clave, nombre, descripcion, categoria, tipo, etapa, fecha, video, esLider) {
            document.getElementById('detallesNombre').textContent = nombre;
            document.getElementById('detallesDescripcion').textContent = descripcion;
            document.getElementById('detallesCategoria').textContent = categoria; 
            document.getElementById('detallesTipo').textContent = tipo;           
            document.getElementById('detallesEtapa').textContent = etapa;         
            document.getElementById('detallesFecha').textContent = fecha;

            const videoContainer = document.getElementById('detallesVideo');
            if (video) {
                videoContainer.innerHTML =
                    `<iframe width="100%" height="315" src="${video.replace("watch?v=", "embed/")}" frameborder="0" allowfullscreen></iframe>`;
            } else {
                videoContainer.innerHTML = '<p>No hay video disponible.</p>';
            }

            // Lógica para mostrar/ocultar el botón "Editar Proyecto"
            const botonEditar = document.getElementById('botonEditarProyecto');
            if (esLider == 1) {
                botonEditar.style.display = 'block';
                botonEditar.href = `/proyectos/${clave}/editar`;
            } else {
                botonEditar.style.display = 'none';
            }

            document.getElementById('detallesProyecto').style.display = 'block';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const primerProyecto = @json($proyectos->first());
            if (primerProyecto) {
                mostrarDetalles(
                    primerProyecto.clave_proyecto,
                    primerProyecto.nombre,
                    primerProyecto.descripcion,
                    primerProyecto.nombre_categoria, 
                    primerProyecto.nombre_tipo,      
                    primerProyecto.nombre_etapa,     
                    primerProyecto.fecha_agregado,
                    primerProyecto.video,
                    primerProyecto.es_lider ?? 0
                );
            }
        });
    </script>

@endsection
