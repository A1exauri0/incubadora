@extends('layouts.layout')

@section('titulo', $titulo)

{{-- Modulo simbologia --}}
@section('modulo_simbologia')
    {{-- Asegúrate de que el ID del modal sea 'simbologiaModal' --}}
    <div class="modal-header">
        <h4 class="modal-title">Simbología</h4>
        {{-- Cambiado de data-bs-dismiss a data-dismiss para Bootstrap 4 --}}
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
        {{-- Cambiado de data-bs-dismiss a data-dismiss para Bootstrap 4 --}}
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    </div>
@endsection

@section('content')

    @hasanyrole('admin|alumno|asesor|mentor')
        <div class="container-fluid">
            <h1 style="text-align: center;">Proyectos</h1>
            <div class="row">
                <!-- Simbología de colores -->
                <div class="col-md-4">
                    {{-- Cambiado data-bs-toggle a data-toggle y data-bs-target a data-target --}}
                    {{-- El target ahora apunta a #simbologiaModal --}}
                    <button class="btn btn-primary" data-toggle="modal" data-target="#simbologiaModal">
                        Ver Simbología
                    </button>

                    <BR></BR>
                    <div style="height: 500px; overflow-y: auto;">
                        <ul class="list-group">
                            @forelse ($proyectos as $proyecto)
                                <li class="list-group-item list-group-item-{{ $proyecto->clase }}"
                                    onclick="mostrarDetalles('{{ $proyecto->clave_proyecto }}', '{{ $proyecto->nombre }}', '{{ $proyecto->descripcion }}', '{{ $proyecto->categoria }}', '{{ $proyecto->tipo }}', '{{ $proyecto->etapa }}', '{{ $proyecto->fecha_agregado }}', '{{ $proyecto->video }}')">
                                    <strong>Nombre:</strong> {{ $proyecto->nombre }}<br>
                                    <strong>Fecha de registro:</strong> {{ $proyecto->fecha_agregado }}
                                </li>
                            @empty
                                <li class="list-group-item">No participa en ningún proyecto.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Detalles del proyecto -->
                <div class="col-md-8">
                    <div id="detallesProyecto" style="display:none;">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title" id="detallesNombre"></h5>
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
        function mostrarDetalles(clave, nombre, descripcion, categoria, tipo, etapa, fecha, video) {
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

            document.getElementById('detallesProyecto').style.display = 'block';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const primerProyecto = @json($proyectos->first());
            if (primerProyecto) {
                mostrarDetalles(
                    primerProyecto.clave_proyecto,
                    primerProyecto.nombre,
                    primerProyecto.descripcion,
                    primerProyecto.categoria,
                    primerProyecto.tipo,
                    primerProyecto.etapa,
                    primerProyecto.fecha_agregado,
                    primerProyecto.video
                );
            }
        });
    </script>

@endsection
