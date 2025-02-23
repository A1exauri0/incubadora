@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registro de Datos</h2>
    <form method="POST" action="{{ route('registro.datos.guardar') }}">
        @csrf
        <div class="mb-3">
            <label for="no_control" class="form-label">Número de Control</label>
            <input type="text" class="form-control" name="no_control" id="no_control" 
                   value="{{ $alumno->no_control ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" id="nombre" 
                   value="{{ $alumno->nombre ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label for="carrera" class="form-label">Carrera</label>
            <input type="text" class="form-control" name="carrera" id="carrera" 
                   value="{{ $alumno->carrera ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" name="telefono" id="telefono" 
                   value="{{ $alumno->telefono ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label for="semestre" class="form-label">Semestre</label>
            <input type="number" class="form-control" name="semestre" id="semestre" 
                   value="{{ $alumno->semestre ?? '' }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
@endsection
