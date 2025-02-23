@extends('layout')

@section('titulo', $titulo)

@section('content')
    <div class="container">
        <h2>Registro de Datos</h2>
        <form method="POST" action="{{ route('registro.datos.guardar') }}">
            @csrf
            {{-- No de control --}}
            <div class="mb-3">
                <label for="no_control" class="form-label">Número de Control</label>
                <input type="text" class="form-control" name="no_control" id="no_control"
                    value="{{ $alumno->no_control ?? '' }}" required>
            </div>
            {{-- Nombre --}}
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" value="{{ $alumno->nombre ?? '' }}"
                    required>
            </div>
            {{-- Carrera --}}
            <div class="mb-3">
                <label for="carrera" class="form-label">Carrera</label>
                <select name="carrera" id="carrera" class="form-select" required>
                    @foreach ($carreras as $carrera)
                        <option value="{{ $carrera->nombre }}"
                            {{ isset($alumno->carrera) && $alumno->carrera == $carrera->nombre ? 'selected' : '' }}>
                            {{ $carrera->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Telefono --}}
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono" id="telefono"
                    value="{{ $alumno->telefono ?? '' }}" required>
            </div>
            {{-- Semestre --}}
            <div class="mb-3">
                <label for="semestre" class="form-label">Semestre</label>
                <select name="semestre" id="semestre" class="form-select" required>
                    <option value="1" {{ isset($alumno->semestre) && $alumno->semestre == 1 ? 'selected' : '' }}>1°
                        semestre</option>
                    <option value="2" {{ isset($alumno->semestre) && $alumno->semestre == 2 ? 'selected' : '' }}>2°
                        semestre</option>
                    <option value="3" {{ isset($alumno->semestre) && $alumno->semestre == 3 ? 'selected' : '' }}>3°
                        semestre</option>
                    <option value="4" {{ isset($alumno->semestre) && $alumno->semestre == 4 ? 'selected' : '' }}>4°
                        semestre</option>
                    <option value="5" {{ isset($alumno->semestre) && $alumno->semestre == 5 ? 'selected' : '' }}>5°
                        semestre</option>
                    <option value="6" {{ isset($alumno->semestre) && $alumno->semestre == 6 ? 'selected' : '' }}>6°
                        semestre</option>
                    <option value="7" {{ isset($alumno->semestre) && $alumno->semestre == 7 ? 'selected' : '' }}>7°
                        semestre</option>
                    <option value="8" {{ isset($alumno->semestre) && $alumno->semestre == 8 ? 'selected' : '' }}>8°
                        semestre</option>
                    <option value="9" {{ isset($alumno->semestre) && $alumno->semestre == 9 ? 'selected' : '' }}>9°
                        semestre</option>
                    <option value="10" {{ isset($alumno->semestre) && $alumno->semestre == 10 ? 'selected' : '' }}>10°
                        semestre</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection
