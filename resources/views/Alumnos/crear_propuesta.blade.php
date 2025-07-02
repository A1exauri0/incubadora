@extends('layouts.layout')

@section('titulo', 'Crear Propuesta de Proyecto')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4 text-center">Crear Nueva Propuesta de Proyecto</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                Detalles del Proyecto
            </div>
            <div class="card-body">
                <form action="{{ route('proyectos.store_proposal') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="clave_proyecto">Clave del Proyecto:</label>
                        <input type="text" class="form-control @error('clave_proyecto') is-invalid @enderror" id="clave_proyecto" name="clave_proyecto" value="{{ old('clave_proyecto') }}" required>
                        @error('clave_proyecto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nombre">Nombre del Proyecto:</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nombre_descriptivo">Nombre Descriptivo:</label>
                        <input type="text" class="form-control @error('nombre_descriptivo') is-invalid @enderror" id="nombre_descriptivo" name="nombre_descriptivo" value="{{ old('nombre_descriptivo') }}" required>
                        @error('nombre_descriptivo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="5" required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="categoria">Categoría:</label>
                        <select class="form-control @error('categoria') is-invalid @enderror" id="categoria" name="categoria" required>
                            <option value="">Selecciona una categoría</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->idCategoria }}" {{ old('categoria') == $cat->idCategoria ? 'selected' : '' }}>
                                    {{ $cat->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo:</label>
                        <select class="form-control @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                            <option value="">Selecciona un tipo</option>
                            @foreach ($tipos as $t)
                                <option value="{{ $t->idTipo }}" {{ old('tipo') == $t->idTipo ? 'selected' : '' }}>
                                    {{ $t->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- El campo de etapa no se muestra, se asigna automáticamente a PENDIENTE (ID 1) --}}
                    <div class="form-group">
                        <label>Etapa del Proyecto:</label>
                        <p class="form-control-static">Se establecerá automáticamente como "PENDIENTE".</p>
                    </div>

                    <div class="form-group">
                        <label for="video">URL del Video (Opcional):</label>
                        <input type="url" class="form-control @error('video') is-invalid @enderror" id="video" name="video" value="{{ old('video') }}" placeholder="Ej: https://www.youtube.com/watch?v=VIDEO_ID">
                        @error('video')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="area_aplicacion">Área de Aplicación (Opcional):</label>
                        <input type="text" class="form-control @error('area_aplicacion') is-invalid @enderror" id="area_aplicacion" name="area_aplicacion" value="{{ old('area_aplicacion') }}">
                        @error('area_aplicacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="naturaleza_tecnica">Naturaleza Técnica (Opcional):</label>
                        <input type="text" class="form-control @error('naturaleza_tecnica') is-invalid @enderror" id="naturaleza_tecnica" name="naturaleza_tecnica" value="{{ old('naturaleza_tecnica') }}">
                        @error('naturaleza_tecnica')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="objetivo">Objetivo (Opcional):</label>
                        <textarea class="form-control @error('objetivo') is-invalid @enderror" id="objetivo" name="objetivo" rows="3">{{ old('objetivo') }}</textarea>
                        @error('objetivo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Enviar Propuesta</button>
                </form>
            </div>
        </div>
    </div>
@endsection
