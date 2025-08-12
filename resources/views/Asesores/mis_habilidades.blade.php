@extends('layouts.layoutCrud')

@section('titulo', 'Mis Habilidades')
@section('administrar', 'Gestión de Mis Habilidades')
@section('administrar_s', 'Habilidad')

{{-- Las secciones de la tabla no son necesarias aquí, se dejan vacías --}}
@section('total_registros', '')
@section('columnas')
@endsection
@section('datos')
@endsection
@section('paginacion')
@endsection
@section('script_checkboxes')
@endsection
@section('modulo_agregar')
@endsection
@section('modulo_editar')
@endsection
@section('modulo_eliminar')
@endsection
@section('modulo_eliminar_multiple')
@endsection
@section('modulo_ver')
@endsection

{{-- CONTENIDO PRINCIPAL DE LA VISTA --}}
@section('content')

<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>Gestión de <b>Mis Habilidades</b></h2>
                    </div>
                    <div class="col-sm-6 text-right">
                        {{-- Botón para abrir el modal de agregar habilidad personalizada --}}
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCustomSkillModal">
                            <i class="fa-solid fa-plus"></i> Añadir Habilidad Personalizada
                        </button>
                    </div>
                </div>
            </div>

            {{-- Sección de Mensajes de éxito y error --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <h4>Habilidades Actuales:</h4>
                        <ul id="habilidades_actuales_list" class="list-group">
                            @forelse ($misHabilidades as $habilidad)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $habilidad->nombre }}
                                    <form action="{{ route('asesor.habilidades.destroy', $habilidad->idHabilidad) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar esta habilidad?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">Aún no tienes habilidades asignadas.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <h4>Añadir Habilidad del Catálogo:</h4>
                        <form action="{{ route('asesor.habilidades.addCatalog') }}" method="POST" class="d-flex align-items-center">
                            @csrf
                            <select id="select_habilidad_disponible" name="idHabilidad" class="form-control mr-2">
                                <option value="">-- Seleccione una habilidad --</option>
                                @foreach ($habilidadesDisponibles as $habilidad)
                                    <option value="{{ $habilidad->idHabilidad }}">{{ $habilidad->nombre }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary" {{ $habilidadesDisponibles->isEmpty() ? 'disabled' : '' }}>
                                Agregar Habilidad
                            </button>
                        </form>
                        @if ($habilidadesDisponibles->isEmpty())
                            <small class="form-text text-muted">No hay más habilidades disponibles para añadir.</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PARA AÑADIR NUEVA HABILIDAD PERSONALIZADA --}}
<div class="modal fade" id="addCustomSkillModal" tabindex="-1" role="dialog" aria-labelledby="addCustomSkillModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomSkillModalLabel">Añadir Habilidad Personalizada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('asesor.habilidades.addCustom') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre_habilidad">Nombre de la Habilidad</label>
                        <input type="text" class="form-control" id="nombre_habilidad" name="nombre_habilidad" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_habilidad">Descripción</label>
                        <textarea class="form-control" id="descripcion_habilidad" name="descripcion_habilidad" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear y Agregar Habilidad</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar los alerts
        $('.alert').alert();
    });
</script>
@endpush