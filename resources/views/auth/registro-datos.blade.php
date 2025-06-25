@extends('layouts.layout')

@section('titulo', $titulo)

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Registro de Datos</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('registro.datos.guardar') }}">
                    @csrf
                    <div class="form-row">
                        {{-- Número de Control --}}
                        <div class="form-group col-md-6">
                            <label for="no_control">Número de Control</label>
                            <input type="text" class="form-control" name="no_control" id="no_control"
                                value="{{ $alumno->no_control ?? '' }}" required>
                        </div>

                        {{-- Nombre --}}
                        <div class="form-group col-md-6">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre"
                                value="{{ $alumno->nombre ?? '' }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        {{-- Carrera --}}
                        <div class="form-group col-md-6">
                            <label for="carrera">Carrera</label>
                            <select name="carrera" id="carrera" class="form-control" required>
                                @foreach ($carreras as $carrera)
                                    <option value="{{ $carrera->nombre }}"
                                        {{ isset($alumno->carrera) && $alumno->carrera == $carrera->nombre ? 'selected' : '' }}>
                                        {{ $carrera->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Teléfono --}}
                        <div class="form-group col-md-6">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" id="telefono"
                                value="{{ $alumno->telefono ?? '' }}" required>
                        </div>
                    </div>

                    {{-- Semestre --}}
                    <div class="form-group">
                        <label for="semestre">Semestre</label>
                        <select name="semestre" id="semestre" class="form-control" required>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}"
                                    {{ isset($alumno->semestre) && $alumno->semestre == $i ? 'selected' : '' }}>
                                    {{ $i }}° semestre
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
