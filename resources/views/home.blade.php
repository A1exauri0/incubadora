@include('layout')

@section('titulo', $titulo)

@section('content')

@extends('layout')

@section('titulo', $titulo)

@section('content')

    <div class="container-fluid" style="padding-top: 30px">
        <h1 style="text-align: center;">Inicio</h1>
        <div class="row">
            <!-- Simbología de colores -->
            <div class="col-md-4">
                <button class="boton-toggle" onclick="toggleContenido()">Simbología</button>
                <ul class="list-group mb-3 contenido-oculto" id="contenido">
                    <p>Los colores de los proyectos indican su estado actual:</p>
                    @foreach ($etapas as $etapa)
                        <li class="list-group-item list-group-item-{{ $etapa->clase }}">
                            <strong>{{ $etapa->color }} ({{ $etapa->nombre }}):</strong> {{ $etapa->descripcion }}
                        </li>
                    @endforeach
                </ul>
                <script>
                    // Función para mostrar y ocultar el contenido
                    function toggleContenido() {
                        var contenido = document.getElementById('contenido');
                        var brElements = document.getElementById('br-elements');
                
                        if (contenido.style.display === "none" || contenido.style.display === "") {
                            contenido.style.display = "block";  // Mostrar el contenido
                            if (brElements) {
                                brElements.style.display = "none";  // Quitar el <br> cuando se muestra el contenido
                            }
                        } else {
                            contenido.style.display = "none";  // Ocultar el contenido
                            if (brElements) {
                                brElements.style.display = "block";  // Usar el <br> cuando el contenido está oculto
                            }
                        }
                    }
                </script>
                
                <div id="br-elements" >
                    <br>
                </div>

                <h2>Proyectos</h2>
                <div style="height: 500px; overflow-y: auto;">
                    <ul class="list-group">
                        @foreach ($proyectos as $proyecto)
                            <li class="list-group-item list-group-item-{{ $proyecto->clase }}"
                                onclick="mostrarDetalles('{{ $proyecto->clave_proyecto }}', '{{ $proyecto->nombre }}', '{{ $proyecto->descripcion }}', '{{ $proyecto->categoria }}', '{{ $proyecto->tipo }}', '{{ $proyecto->etapa }}', '{{ $proyecto->fecha_agregado }}', '{{ $proyecto->video }}')"
                                style="user-select: none;">
                                <strong>Nombre:</strong> <span
                                    style="user-select: none;">{{ $proyecto->nombre }}</span><br>
                                <strong>Fecha de registro:</strong> <span
                                    style="user-select: none;">{{ $proyecto->fecha_agregado }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Detalles del proyecto -->
            <div class="col-md-8">
                <div id="detallesProyecto" style="display:none;">
                    <h3>Detalles del Proyecto</h3>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" id="detallesNombre"></h5>
                            <p class="card-text"><strong>Descripción:</strong> <span id="detallesDescripcion"></span></p>
                            <p class="card-text"><strong>Categoría:</strong> <span id="detallesCategoria"></span></p>
                            <p class="card-text"><strong>Tipo:</strong> <span id="detallesTipo"></span></p>
                            <p class="card-text"><strong>Etapa:</strong> <span id="detallesEtapa"></span></p>
                            <p class="card-text"><strong>Fecha de registro:</strong> <span id="detallesFecha"></span></p>

                            <!-- Aquí se muestra el video -->
                            <div id="detallesVideo" style="margin-top: 20px;">
                                <!-- El video se incrustará aquí -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const categorias = @json($categorias);
        const tipos = @json($tipos);
        const etapas = @json($etapas);

        function mostrarDetalles(clave, nombre, descripcion, idCategoria, idTipo, idEtapa, fecha, video) {
            const categoriaObj = categorias.find(c => c.idCategoria == idCategoria);
            const tipoObj = tipos.find(t => t.idTipo == idTipo);
            const etapaObj = etapas.find(e => e.idEtapa == idEtapa);

            const categoria = categoriaObj ? categoriaObj.nombre : 'Categoría no asignada';
            const tipo = tipoObj ? tipoObj.nombre : 'Tipo no asignado';
            const etapa = etapaObj ? etapaObj.nombre : 'Etapa no asignada';

            document.getElementById('detallesNombre').textContent = nombre;
            document.getElementById('detallesDescripcion').textContent = descripcion;
            document.getElementById('detallesCategoria').textContent = categoria;
            document.getElementById('detallesTipo').textContent = tipo;
            document.getElementById('detallesEtapa').textContent = etapa;
            document.getElementById('detallesFecha').textContent = fecha;

            // Incrustar video si hay una URL válida
            const videoContainer = document.getElementById('detallesVideo');
            if (video) {
                const iframe = document.createElement('iframe');
                iframe.src = video.replace("watch?v=", "embed/"); // Para videos de YouTube
                iframe.width = "100%";
                iframe.height = "315px";
                iframe.frameBorder = "0";
                iframe.allowFullscreen = true;
                videoContainer.innerHTML = '';
                videoContainer.appendChild(iframe);
            } else {
                videoContainer.innerHTML = '<p>No hay video disponible.</p>';
            }

            document.getElementById('detallesProyecto').style.display = 'block';
        }
    </script>

@endsection

@endsection