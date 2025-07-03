@extends('layouts.layout')

@section('titulo', $titulo)

@section('content')
    <div class="container-fluid mt-4">
        <h1 class="mb-4 text-center">{{ $titulo }}</h1>

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
                Listado de Propuestas
            </div>
            <div class="card-body">
                @if ($propuestas->isEmpty())
                    <p class="text-center">No hay propuestas de proyectos para revisar.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Clave</th>
                                    <th>Nombre del Proyecto</th>
                                    <th>Categoría</th>
                                    <th>Tipo</th>
                                    <th>Etapa Actual</th>
                                    <th>Fecha Envío</th>
                                    <th>Estado Propuesta</th>
                                    <th>Motivo Rechazo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($propuestas as $propuesta)
                                    <tr>
                                        <td>{{ $propuesta->clave_proyecto }}</td>
                                        <td>{{ $propuesta->nombre }}</td>
                                        <td>{{ $propuesta->nombre_categoria }}</td>
                                        <td>{{ $propuesta->nombre_tipo }}</td>
                                        <td>{{ $propuesta->nombre_etapa }}</td>
                                        <td>{{ \Carbon\Carbon::parse($propuesta->fecha_agregado)->format('d/m/Y') }}</td>
                                        <td>
                                            {{-- Lógica para mostrar el estado de la propuesta basada en el ID de la etapa --}}
                                            @if ($propuesta->etapa == \App\Http\Controllers\ProyectoController::ID_ETAPA_PENDIENTE)
                                                <span class="badge badge-warning">Pendiente</span>
                                            @elseif ($propuesta->etapa == \App\Http\Controllers\ProyectoController::ID_ETAPA_APROBADA)
                                                <span class="badge badge-success">Aprobada</span>
                                            @elseif ($propuesta->etapa == \App\Http\Controllers\ProyectoController::ID_ETAPA_RECHAZADA)
                                                <span class="badge badge-danger">Rechazada</span>
                                            @else
                                                <span class="badge badge-info">{{ $propuesta->nombre_etapa }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $propuesta->motivo_rechazo ?? 'N/A' }}</td>
                                        <td>
                                            @if ($propuesta->etapa == \App\Http\Controllers\ProyectoController::ID_ETAPA_PENDIENTE)
                                                <form action="{{ route('admin.proyectos.propuestas.review', $propuesta->clave_proyecto) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="action" value="accept">
                                                    <button type="submit" class="btn btn-success btn-sm" title="Aceptar Propuesta">
                                                        <i class="fas fa-check"></i> Aceptar
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal" data-clave="{{ $propuesta->clave_proyecto }}" title="Rechazar Propuesta">
                                                    <i class="fas fa-times"></i> Rechazar
                                                </button>
                                            @else
                                                <span class="text-muted">Revisado</span>
                                            @endif
                                            {{-- Botón para generar PDF --}}
                                            <a href="{{ route('admin.proyectos.ficha_tecnica_pdf', $propuesta->clave_proyecto) }}" class="btn btn-info btn-sm ml-1" title="Generar Ficha Técnica PDF" target="_blank">
                                                <i class="fas fa-file-pdf"></i> PDF
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal para rechazar propuesta -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="rejectForm" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <input type="hidden" name="clave_proyecto" id="rejectClaveProyecto">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Rechazar Propuesta de Proyecto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="motivo_rechazo">Motivo del Rechazo:</label>
                            <textarea class="form-control" id="motivo_rechazo" name="motivo_rechazo" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Rechazar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $('#rejectModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var claveProyecto = button.data('clave'); // Extraer información de los atributos data-*
        var modal = $(this);
        modal.find('#rejectClaveProyecto').val(claveProyecto);
        modal.find('#rejectForm').attr('action', '/admin/proyectos/propuestas/' + claveProyecto + '/review');
        modal.find('#motivo_rechazo').val(''); // Limpiar el campo de motivo cada vez que se abre
    });
</script>
@endpush
