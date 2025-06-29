@extends('layouts.layout')

@section('titulo', 'Editar Proyecto: ' . $proyecto->nombre)

@section('content')
{{-- Nota: El div.container del layout debe ser div.container-fluid para el efecto completo --}}
<div class="row justify-content-center mt-4 mb-4">
    {{-- Manteniendo col-12 col-md-12 col-lg-10. Ahora que el padre es container-fluid, este 10/12 se aplicará sobre un ancho total. --}}
    <div class="col-12 col-md-12 col-lg-10">
        <div class="card shadow-lg rounded-lg">
            <div class="card-header bg-gradient-primary text-white text-center py-3 rounded-top">
                <h2 class="mb-0">Editar datos del Proyecto: <span class="font-weight-bold">{{ $proyecto->nombre }}</span></h2>
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

                <form action="{{ route('proyectos.update', $proyecto->clave_proyecto) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Necesario para el método PUT en Laravel -->

                    {{-- Clave del Proyecto (solo lectura) --}}
                    <div class="form-group mb-4">
                        <label for="clave_proyecto" class="font-weight-bold">Clave del Proyecto:</label>
                        <input type="text" class="form-control" id="clave_proyecto" value="{{ $proyecto->clave_proyecto }}" disabled readonly>
                        <small class="form-text text-muted">La clave del proyecto no se puede modificar.</small>
                    </div>

                    {{-- Primera fila: Nombre y Nombre Descriptivo --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="nombre" class="font-weight-bold">Nombre del Proyecto:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $proyecto->nombre) }}" required maxlength="50">
                                @error('nombre')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="nombre_descriptivo" class="font-weight-bold">Nombre Descriptivo:</label>
                                <input type="text" class="form-control" id="nombre_descriptivo" name="nombre_descriptivo" value="{{ old('nombre_descriptivo', $proyecto->nombre_descriptivo) }}" required maxlength="100">
                                @error('nombre_descriptivo')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div class="form-group mb-3">
                        <label for="descripcion" class="font-weight-bold">Descripción:</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required maxlength="800">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Segunda fila: Categoría y Tipo --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="categoria" class="font-weight-bold">Categoría:</label>
                                <select class="form-control" id="categoria" name="categoria" required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->idCategoria }}" {{ old('categoria', $proyecto->categoria) == $categoria->idCategoria ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="tipo" class="font-weight-bold">Tipo:</label>
                                <select class="form-control" id="tipo" name="tipo" required>
                                    <option value="">Seleccione un tipo</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo->idTipo }}" {{ old('tipo', $proyecto->tipo) == $tipo->idTipo ? 'selected' : '' }}>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                    </select>
                                @error('tipo')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Tercera fila: Etapa y Área de Aplicación --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="etapa" class="font-weight-bold">Etapa:</label>
                                <select class="form-control" id="etapa" name="etapa" required>
                                    <option value="">Seleccione una etapa</option>
                                    @foreach($etapas as $etapa)
                                        <option value="{{ $etapa->idEtapa }}" {{ old('etapa', $proyecto->etapa) == $etapa->idEtapa ? 'selected' : '' }}>
                                            {{ $etapa->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('etapa')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="area_aplicacion" class="font-weight-bold">Área de Aplicación:</label>
                                <input type="text" class="form-control" id="area_aplicacion" name="area_aplicacion" value="{{ old('area_aplicacion', $proyecto->area_aplicacion) }}" required maxlength="50">
                                @error('area_aplicacion')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Naturaleza Técnica --}}
                    <div class="form-group mb-3">
                        <label for="naturaleza_tecnica" class="font-weight-bold">Naturaleza Técnica:</label>
                        <input type="text" class="form-control" id="naturaleza_tecnica" name="naturaleza_tecnica" value="{{ old('naturaleza_tecnica', $proyecto->naturaleza_tecnica) }}" required maxlength="50">
                        @error('naturaleza_tecnica')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Objetivo --}}
                    <div class="form-group mb-3">
                        <label for="objetivo" class="font-weight-bold">Objetivo:</label>
                        <textarea class="form-control" id="objetivo" name="objetivo" rows="4" required maxlength="600">{{ old('objetivo', $proyecto->objetivo) }}</textarea>
                        @error('objetivo')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- URL del Video --}}
                    <div class="form-group mb-4">
                        <label for="video" class="font-weight-bold">URL del Video (opcional):</label>
                        <input type="text" class="form-control" id="video" name="video" value="{{ old('video', $proyecto->video) }}" placeholder="Ej: https://www.youtube.com/watch?v=xxxxxxxx">
                        <small class="form-text text-muted">Deja vacío si no hay video. Debe comenzar con 'http://' o 'https://'.</small>
                        @error('video')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-success flex-grow-1 mr-2 py-2 rounded-pill shadow">
                            <i class="fas fa-save mr-2"></i> Guardar Cambios
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-secondary flex-grow-1 ml-2 py-2 rounded-pill shadow">
                            <i class="fas fa-times-circle mr-2"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('head')
    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endpush

@push('scripts')
    <script>
        // Script para animar los alerts si lo deseas
        $(document).ready(function() {
            setTimeout(function() {
                $(".alert").alert('close');
            }, 5000); // Los alerts se cierran automáticamente después de 5 segundos
        });
    </script>
@endpush

<style>
    /* Estilos personalizados para mejorar la vista */
    .card {
        border-radius: 1rem; /* Bordes más redondeados para las tarjetas */
        border: none; /* Eliminar borde por defecto */
    }
    .card-header {
        border-bottom: none;
        background-image: linear-gradient(to right, #007bff, #0056b3); /* Degradado azul */
        border-radius: 1rem 1rem 0 0 !important; /* Asegurar bordes redondeados arriba */
    }
    .card-body {
        background-color: #fefefe;
        border-radius: 0 0 1rem 1rem !important; /* Asegurar bordes redondeados abajo */
    }
    .form-group label {
        color: #333;
        font-weight: 600;
    }
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .btn-success {
        background-image: linear-gradient(to right, #28a745, #218838);
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
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }
    .rounded-lg {
        border-radius: 1rem!important;
    }
    .rounded-pill {
        border-radius: 50rem!important;
    }
    .bg-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
    }
</style>
@endsection
