@extends('layouts.layout')

@section('titulo', $titulo)

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-3 rounded-top">
                        <h5 class="mb-0">{{ $titulo }}</h5>
                    </div>
                    <div class="card-body p-4">
                        {{-- Mensajes de Sesión (aunque pediste quitarlos, es bueno tener una referencia de dónde irían si los quieres de vuelta) --}}
                        {{-- @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif --}}

                        <form method="POST" action="{{ route('registro.datos.guardar') }}">
                            @csrf

                            {{-- Formulario para Alumno --}}
                            @if (Auth::user()->hasRole('alumno') && (isset($data['rol_type']) && $data['rol_type'] === 'alumno'))
                                <h6 class="mb-3 text-center text-info">Completa tus datos como Alumno</h6>
                                <div class="form-row d-flex flex-wrap">
                                    <div class="form-group col-md-6 mb-3 px-2">
                                        <label for="no_control">Número de Control:</label>
                                        <input type="text" class="form-control @error('no_control') is-invalid @enderror" name="no_control" id="no_control"
                                            value="{{ old('no_control', $data['user_data']->no_control ?? '') }}" required maxlength="8">
                                        @error('no_control')
                                            <div class="invalid-feedback">{{ $errors->first('no_control') }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 mb-3 px-2">
                                        <label for="nombre">Nombre Completo:</label>
                                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" id="nombre"
                                            value="{{ old('nombre', $data['user_data']->nombre ?? '') }}" required maxlength="50">
                                        @error('nombre')
                                            <div class="invalid-feedback">{{ $errors->first('nombre') }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row d-flex flex-wrap">
                                    <div class="form-group col-md-6 mb-3 px-2">
                                        <label for="carrera">Carrera:</label>
                                        <select name="carrera" id="carrera" class="form-control @error('carrera') is-invalid @enderror" required>
                                            <option value="">Seleccione su carrera</option>
                                            @if(isset($data['carreras']))
                                                @foreach ($data['carreras'] as $carrera)
                                                    <option value="{{ $carrera->nombre }}" {{ old('carrera', $data['user_data']->carrera ?? '') == $carrera->nombre ? 'selected' : '' }}>
                                                        {{ $carrera->nombre }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('carrera')
                                            <div class="invalid-feedback">{{ $errors->first('carrera') }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 mb-3 px-2">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" id="telefono"
                                            value="{{ old('telefono', $data['user_data']->telefono ?? '') }}" required maxlength="10">
                                        @error('telefono')
                                            <div class="invalid-feedback">{{ $errors->first('telefono') }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="semestre">Semestre:</label>
                                    <select name="semestre" id="semestre" class="form-control @error('semestre') is-invalid @enderror" required>
                                        <option value="">Seleccione su semestre</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ old('semestre', $data['user_data']->semestre ?? '') == $i ? 'selected' : '' }}>
                                                {{ $i }}° semestre
                                            </option>
                                        @endfor
                                    </select>
                                    @error('semestre')
                                        <div class="invalid-feedback">{{ $errors->first('semestre') }}</div>
                                    @enderror
                                </div>
                            @endif

                            {{-- Formulario para Asesor --}}
                            @if (Auth::user()->hasRole('asesor') && (isset($data['rol_type']) && $data['rol_type'] === 'asesor'))
                                <h6 class="mb-3 text-center text-success">Completa tus datos como Asesor</h6>
                                <div class="form-group mb-3">
                                    <label for="nombre">Nombre Completo:</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" id="nombre"
                                        value="{{ old('nombre', $data['user_data']->nombre ?? '') }}" required maxlength="50">
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $errors->first('nombre') }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-4">
                                    <label for="telefono">Teléfono:</label>
                                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" id="telefono"
                                        value="{{ old('telefono', $data['user_data']->telefono ?? '') }}" required maxlength="10">
                                    @error('telefono')
                                        <div class="invalid-feedback">{{ $errors->first('telefono') }}</div>
                                    @enderror
                                </div>
                                <p class="text-muted small">Tu correo electrónico: <strong>{{ Auth::user()->email }}</strong> será asociado automáticamente.</p>
                            @endif

                            {{-- Formulario para Mentor --}}
                            @if (Auth::user()->hasRole('mentor') && (isset($data['rol_type']) && $data['rol_type'] === 'mentor'))
                                <h6 class="mb-3 text-center text-warning">Completa tus datos como Mentor</h6>
                                <div class="form-group mb-3">
                                    <label for="nombre">Nombre Completo:</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" id="nombre"
                                        value="{{ old('nombre', $data['user_data']->nombre ?? '') }}" required maxlength="50">
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $errors->first('nombre') }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-4">
                                    <label for="telefono">Teléfono:</label>
                                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" id="telefono"
                                        value="{{ old('telefono', $data['user_data']->telefono ?? '') }}" required maxlength="10">
                                    @error('telefono')
                                        <div class="invalid-feedback">{{ $errors->first('telefono') }}</div>
                                    @enderror
                                </div>
                                <p class="text-muted small">Tu correo electrónico: <strong>{{ Auth::user()->email }}</strong> será asociado automáticamente.</p>
                            @endif

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">
                                    <i class="fas fa-save me-2"></i> Guardar Mis Datos
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const telefonoInputs = document.querySelectorAll('#telefono');
        telefonoInputs.forEach(function(input) {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, ''); // Eliminar cualquier caracter que no sea un dígito
                if (this.value.length > 10) {
                    this.value = this.value.substring(0, 10); // Limitar a 10 dígitos
                }
            });
        });

        const noControlInput = document.getElementById('no_control');
        if (noControlInput) { // Asegurarse de que el campo exista antes de añadir el listener
            noControlInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, ''); // Solo dígitos
                if (this.value.length > 8) {
                    this.value = this.value.substring(0, 8); // Limitar a 8 dígitos
                }
            });
        }
    });
</script>
@endpush

<style>
    /* Estilos adicionales para la vista, si tu layout no los provee */
    .card {
        border-radius: 1rem;
    }
    .card-header {
        background: linear-gradient(45deg, #007bff, #0056b3); /* Degradado azul */
        border-radius: 1rem 1rem 0 0 !important;
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        transform: translateY(-2px);
    }
    .form-group label {
        font-weight: 600;
        color: #343a40;
    }
    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        border-color: #007bff;
    }
    .invalid-feedback {
        display: block; /* Asegura que el mensaje de error se muestre */
    }
    /* Estilo para los "form-row" para que se vean bien con Bootstrap 5 y d-flex */
    .form-row > .form-group {
        padding-left: .75rem; /* Ajuste para espaciado de columnas si usas Bootstrap grid */
        padding-right: .75rem;
    }
</style>
