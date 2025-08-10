@extends('layouts.layoutCrud')

@section('titulo', $titulo)

@section('administrar', 'Propuestas de Proyectos para Revisión')
@section('administrar_s', 'Propuesta de Proyecto')

@section('total_registros', $propuestas->total())

@section('modulo_agregar')@endsection
@section('modulo_eliminar_multiple')@endsection

@section('columnas')
    <th>Clave</th>
    <th>Nombre del Proyecto</th>
    <th>Categoría</th>
    <th>Tipo</th>
    <th>Etapa Actual</th>
    <th>Fecha Envío</th>
    <th>Motivo Rechazo</th>
    <th>Acciones</th>
@endsection

@section('datos')
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

    @if ($propuestas->isEmpty())
        <tr>
            <td colspan="8" class="text-center">No hay propuestas de proyectos pendientes para tu revisión.</td>
        </tr>
    @else
        @foreach ($propuestas as $propuesta)
            <tr>
                <td>{{ $propuesta->clave_proyecto }}</td>
                <td>{{ $propuesta->nombre }}</td>
                <td>{{ $propuesta->nombre_categoria }}</td>
                <td>{{ $propuesta->nombre_tipo }}</td>
                <td>{{ $propuesta->nombre_etapa }}</td>
                <td>{{ \Carbon\Carbon::parse($propuesta->fecha_agregado)->format('d/m/Y') }}</td>
                <td>{{ $propuesta->motivo_rechazo ?? 'N/A' }}</td>
                <td>
                    {{-- Acciones del asesor: Aceptar (Visto Bueno) o Rechazar --}}
                    @if ($propuesta->etapa == 1) {{-- PENDIENTE (pendiente para el asesor) --}}
                        <form action="{{ route('asesor.proyectos.propuestas.review', $propuesta->clave_proyecto) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="action" value="accept">
                            <button type="submit" class="btn btn-success btn-sm" title="Dar Visto Bueno">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal" data-clave="{{ $propuesta->clave_proyecto }}" title="Rechazar Propuesta">
                            <i class="fas fa-times"></i>
                        </button>
                    @else
                        {{-- Si la propuesta ya fue revisada por el asesor (aprobada/rechazada), solo muestra "Revisado" --}}
                        <span class="text-muted">Ya revisado</span>
                    @endif
                    {{-- Enlace para generar la ficha técnica en PDF --}}
                    <a href="{{ route('admin.proyectos.ficha_tecnica_pdf', $propuesta->clave_proyecto) }}" class="btn btn-info btn-sm ml-1" title="Generar Ficha Técnica PDF" target="_blank">
                        <i class="fas fa-file-pdf"></i> 
                    </a>
                </td>
            </tr>
        @endforeach
    @endif
@endsection

@section('paginacion')
    <div class="hint-text">
        Mostrando <b>{{ $propuestas->firstItem() }}</b> a <b>{{ $propuestas->lastItem() }}</b> de <b>{{ $propuestas->total() }}</b> registros.
    </div>
    <ul class="pagination">
        <li class="page-item {{ $propuestas->onFirstPage() ? 'disabled' : '' }}">
            <a href="{{ $propuestas->previousPageUrl() }}" class="page-link">Anterior</a>
        </li>
        @for ($i = 1; $i <= $propuestas->lastPage(); $i++)
            <li class="page-item {{ $i == $propuestas->currentPage() ? 'active' : '' }}"><a href="{{ $propuestas->url($i) }}"
                    class="page-link">{{ $i }}</a></li>
        @endfor
        <li class="page-item {{ $propuestas->onLastPage() ? 'disabled' : '' }}">
            <a href="{{ $propuestas->nextPageUrl() }}" class="page-link">Siguiente</a>
        </li>
    </ul>
@endsection

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

@push('scripts')
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();

        $('#rejectModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var claveProyecto = button.data('clave');
            var modal = $(this);
            modal.find('#rejectClaveProyecto').val(claveProyecto);
            // La acción del formulario para el asesor
            modal.find('#rejectForm').attr('action', '{{ url("/asesor/propuestas") }}/' + claveProyecto + '/review');
            modal.find('#motivo_rechazo').val('');
        });
    });
</script>
@endpush
