@extends('layouts.layout')

@section('titulo', 'Crear Propuesta de Proyecto')

@section('content')
    <div class="row justify-content-center mt-4 mb-4">
        <div class="col-12 col-md-12 col-lg-10">
            <div class="card shadow-lg rounded-lg">
                {{-- Asegúrate de que el card-header tenga las clases correctas --}}
                <div class="card-header bg-gradient-primary text-white text-center py-3 rounded-top">
                    {{-- Asegura que el texto sea blanco --}}
                    <h2 class="mb-0 text-white">Crear Nueva Propuesta de Proyecto</h2>
                </div>
                <div class="card-body p-4">
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

                    <form action="{{ route('proyectos.store_proposal') }}" method="POST">
                        @csrf

                        {{-- Clave del Proyecto --}}
                        <div class="form-group mb-3">
                            <label for="clave_proyecto" class="font-weight-bold">Clave del Proyecto:</label>
                            <input type="text" class="form-control @error('clave_proyecto') is-invalid @enderror"
                                id="clave_proyecto" name="clave_proyecto" value="{{ old('clave_proyecto') }}" required>
                            @error('clave_proyecto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nombre y Nombre Descriptivo --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="nombre" class="font-weight-bold">Nombre del Proyecto:</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                        id="nombre" name="nombre" value="{{ old('nombre') }}" required maxlength="50">
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="nombre_descriptivo" class="font-weight-bold">Nombre Descriptivo:</label>
                                    <input type="text"
                                        class="form-control @error('nombre_descriptivo') is-invalid @enderror"
                                        id="nombre_descriptivo" name="nombre_descriptivo"
                                        value="{{ old('nombre_descriptivo') }}" required maxlength="100">
                                    @error('nombre_descriptivo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Descripción --}}
                        <div class="form-group mb-3">
                            <label for="descripcion" class="font-weight-bold">Descripción:</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion"
                                rows="5" required maxlength="800">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Categoría y Tipo --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="categoria" class="font-weight-bold">Categoría:</label>
                                    <select class="form-control @error('categoria') is-invalid @enderror" id="categoria"
                                        name="categoria" required>
                                        <option value="">Selecciona una categoría</option>
                                        @foreach ($categorias as $cat)
                                            <option value="{{ $cat->idCategoria }}"
                                                {{ old('categoria') == $cat->idCategoria ? 'selected' : '' }}>
                                                {{ $cat->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categoria')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="tipo" class="font-weight-bold">Tipo:</label>
                                    <select class="form-control @error('tipo') is-invalid @enderror" id="tipo"
                                        name="tipo" required>
                                        <option value="">Selecciona un tipo</option>
                                        @foreach ($tipos as $t)
                                            <option value="{{ $t->idTipo }}"
                                                {{ old('tipo') == $t->idTipo ? 'selected' : '' }}>
                                                {{ $t->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tipo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Área de Aplicación --}}
                        <div class="form-group mb-3">
                            <label for="area_aplicacion" class="font-weight-bold">Área de Aplicación (Opcional):</label>
                            <input type="text" class="form-control @error('area_aplicacion') is-invalid @enderror"
                                id="area_aplicacion" name="area_aplicacion" value="{{ old('area_aplicacion') }}"
                                maxlength="50">
                            @error('area_aplicacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Naturaleza Técnica --}}
                        <div class="form-group mb-3">
                            <label for="naturaleza_tecnica" class="font-weight-bold">Naturaleza Técnica (Opcional):</label>
                            <input type="text" class="form-control @error('naturaleza_tecnica') is-invalid @enderror"
                                id="naturaleza_tecnica" name="naturaleza_tecnica" value="{{ old('naturaleza_tecnica') }}"
                                maxlength="50">
                            @error('naturaleza_tecnica')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Objetivo --}}
                        <div class="form-group mb-3">
                            <label for="objetivo" class="font-weight-bold">Objetivo:</label>
                            <textarea class="form-control @error('objetivo') is-invalid @enderror" id="objetivo" name="objetivo" rows="3"
                                maxlength="600" required>{{ old('objetivo') }}</textarea>
                            @error('objetivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- URL del Video --}}
                        <div class="form-group mb-3">
                            <label for="video" class="font-weight-bold">URL del Video (Opcional):</label>
                            <input type="url" class="form-control @error('video') is-invalid @enderror"
                                id="video" name="video" value="{{ old('video') }}"
                                placeholder="Ej: https://www.youtube.com/watch?v=VIDEO_ID">
                            <small class="form-text text-muted">Deja vacío si no hay video. Debe comenzar con 'http://' o
                                'https://'.</small>
                            @error('video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h3>Requerimientos del Proyecto</h3>
                        <div id="requerimientos-container">
                            {{-- Los requerimientos se añadirán aquí dinámicamente --}}
                            @if (old('requerimientos'))
                                @foreach (old('requerimientos') as $index => $req)
                                    <div class="form-row mb-2 dynamic-field">
                                        <div class="col-md-6">
                                            <input type="text" name="requerimientos[{{ $index }}][descripcion]"
                                                class="form-control @error("requerimientos.$index.descripcion") is-invalid @enderror"
                                                placeholder="Descripción del requerimiento"
                                                value="{{ $req['descripcion'] ?? '' }}" required>
                                            @error("requerimientos.$index.descripcion")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="requerimientos[{{ $index }}][cantidad]"
                                                class="form-control @error("requerimientos.$index.cantidad") is-invalid @enderror"
                                                placeholder="Cantidad (ej: 5 unidades, 2 horas)"
                                                value="{{ $req['cantidad'] ?? '' }}" required>
                                            @error("requerimientos.$index.cantidad")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button"
                                                class="btn btn-danger btn-remove-field">Eliminar</button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="form-row mb-2 dynamic-field">
                                    <div class="col-md-6">
                                        <input type="text" name="requerimientos[0][descripcion]" class="form-control"
                                            placeholder="Descripción del requerimiento" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="requerimientos[0][cantidad]" class="form-control"
                                            placeholder="Cantidad (ej: 5 unidades, 2 horas)" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-remove-field">Eliminar</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-secondary mb-4" id="add-requerimiento">
                            <i class="fas fa-plus"></i> Añadir Requerimiento
                        </button>

                        <hr class="my-4">
                        <h3>Resultados Esperados del Proyecto</h3>
                        <div id="resultados-container">
                            {{-- Los resultados se añadirán aquí dinámicamente --}}
                            @if (old('resultados'))
                                @foreach (old('resultados') as $index => $res)
                                    <div class="form-row mb-2 dynamic-field">
                                        <div class="col-md-10">
                                            <input type="text" name="resultados[{{ $index }}][descripcion]"
                                                class="form-control @error("resultados.$index.descripcion") is-invalid @enderror"
                                                placeholder="Descripción del resultado"
                                                value="{{ $res['descripcion'] ?? '' }}" required>
                                            @error("resultados.$index.descripcion")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button"
                                                class="btn btn-danger btn-remove-field">Eliminar</button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="form-row mb-2 dynamic-field">
                                    <div class="col-md-10">
                                        <input type="text" name="resultados[0][descripcion]" class="form-control"
                                            placeholder="Descripción del resultado" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-remove-field">Eliminar</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-secondary mb-4" id="add-resultado">
                            <i class="fas fa-plus"></i> Añadir Resultado
                        </button>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary flex-grow-1 mr-2 py-2 rounded-pill shadow">
                                <i class="fas fa-paper-plane mr-2"></i> Enviar Propuesta
                            </button>
                            <a href="{{ route('home') }}"
                                class="btn btn-secondary flex-grow-1 ml-2 py-2 rounded-pill shadow">
                                <i class="fas fa-times-circle mr-2"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Mover los estilos al final del body con push('scripts') para mayor especificidad --}}
@push('scripts')
    <!-- Font Awesome para íconos (si no se carga globalmente) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        /* Estilos personalizados para mejorar la vista */
        .card {
            border-radius: 1rem;
            /* Bordes más redondeados para las tarjetas */
            border: none;
            /* Eliminar borde por defecto */
        }

        .card-header {
            border-bottom: none;
            /* Aplicar degradado con !important para asegurar la prevalencia */
            background-image: linear-gradient(to right, #007bff, #0056b3) !important;
            border-radius: 1rem 1rem 0 0 !important;
            /* Asegurar bordes redondeados arriba */
        }

        .card-body {
            background-color: #fefefe;
            border-radius: 0 0 1rem 1rem !important;
            /* Asegurar bordes redondeados abajo */
        }

        .form-group label {
            color: #333;
            font-weight: 600;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background-image: linear-gradient(to right, #007bff, #0056b3);
            border: none;
        }

        .btn-secondary {
            background-image: linear-gradient(to right, #6c757d, #5a6268);
            border: none;
        }

        .btn-block {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-block i {
            margin-right: 8px;
        }

        .shadow-lg {
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
        }

        .rounded-lg {
            border-radius: 1rem !important;
        }

        .rounded-pill {
            border-radius: 50rem !important;
        }

        .bg-gradient-primary {
            /* Asegurar que el background se aplique con !important */
            background: linear-gradient(45deg, #007bff, #0056b3) !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Script para animar los alerts si lo deseas
            setTimeout(function() {
                $(".alert").alert('close');
            }, 5000); // Los alerts se cierran automáticamente después de 5 segundos

            // Función para añadir campos dinámicos de requerimientos
            $('#add-requerimiento').on('click', function() {
                const container = $('#requerimientos-container');
                const index = container.children('.dynamic-field').length;

                const newField = `
                    <div class="form-row mb-2 dynamic-field">
                        <div class="col-md-6">
                            <input type="text" name="requerimientos[${index}][descripcion]" class="form-control" placeholder="Descripción del requerimiento" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="requerimientos[${index}][cantidad]" class="form-control" placeholder="Cantidad (ej: 5 unidades, 2 horas)" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-remove-field">Eliminar</button>
                        </div>
                    </div>
                `;
                container.append(newField);
            });

            // Función para añadir campos dinámicos de resultados
            $('#add-resultado').on('click', function() {
                const container = $('#resultados-container');
                const index = container.children('.dynamic-field').length;

                const newField = `
                    <div class="form-row mb-2 dynamic-field">
                        <div class="col-md-10">
                            <input type="text" name="resultados[${index}][descripcion]" class="form-control" placeholder="Descripción del resultado" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-remove-field">Eliminar</button>
                        </div>
                    </div>
                `;
                container.append(newField);
            });

            // Función para eliminar campos dinámicos
            $(document).on('click', '.btn-remove-field', function() {
                $(this).closest('.dynamic-field').remove();
            });
        });
    </script>
@endpush
