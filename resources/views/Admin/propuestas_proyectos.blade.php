@extends('layouts.layoutCrud')

@section('titulo', $titulo)

{{-- Se define qué se va a administrar en plural y singular para el encabezado del CRUD --}}
@section('administrar', 'Propuestas de Proyectos')
@section('administrar_s', 'Propuesta de Proyecto')

{{-- Se pasa el total de registros para el texto de información de paginación --}}
@section('total_registros', $propuestas->total())

{{-- No se utiliza la sección 'script_checkboxes' aquí, ya que esta vista es para revisión y no incluye eliminación múltiple directa por checkboxes --}}

@section('columnas')
    {{-- Las columnas de la tabla. No incluimos la columna de checkbox de selección múltiple. --}}
    <th>Clave</th>
    <th>Nombre del Proyecto</th>
    <th>Categoría</th>
    <th>Tipo</th>
    <th>Etapa Actual</th>
    <th>Fecha Envío</th>
    <th>Estado Propuesta</th>
    <th>Motivo Rechazo</th>
    <th>Acciones</th>
@endsection

@section('datos')
    {{-- Mensajes de sesión (éxito o error) se muestran aquí, si el layout no los maneja globalmente --}}
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
        {{-- Si no hay propuestas, se muestra un mensaje en una fila que abarca todas las columnas --}}
        <tr>
            <td colspan="9" class="text-center">No hay propuestas de proyectos para revisar.</td>
        </tr>
    @else
        {{-- Itera sobre cada propuesta paginada y muestra los datos en la tabla --}}
        @foreach ($propuestas as $propuesta)
            <tr>
                <td>{{ $propuesta->clave_proyecto }}</td>
                <td>{{ $propuesta->nombre }}</td>
                <td>{{ $propuesta->nombre_categoria }}</td>
                <td>{{ $propuesta->nombre_tipo }}</td>
                <td>{{ $propuesta->nombre_etapa }}</td>
                <td>{{ \Carbon\Carbon::parse($propuesta->fecha_agregado)->format('d/m/Y') }}</td>
                <td>
                    {{-- Lógica para mostrar el estado de la propuesta con insignias (badges) --}}
                    @if ($propuesta->etapa == \App\Http\Controllers\PropuestaProyectoController::ID_ETAPA_PENDIENTE)
                        <span class="badge badge-warning">Pendiente</span>
                    @elseif ($propuesta->etapa == \App\Http\Controllers\PropuestaProyectoController::ID_ETAPA_APROBADA)
                        <span class="badge badge-success">Aprobada</span>
                    @elseif ($propuesta->etapa == \App\Http\Controllers\PropuestaProyectoController::ID_ETAPA_RECHAZADA)
                        <span class="badge badge-danger">Rechazada</span>
                    @else
                        <span class="badge badge-info">{{ $propuesta->nombre_etapa }}</span>
                    @endif
                </td>
                <td>{{ $propuesta->motivo_rechazo ?? 'N/A' }}</td>
                <td>
                    {{-- Acciones (Aceptar, Rechazar, PDF) --}}
                    @if ($propuesta->etapa == \App\Http\Controllers\PropuestaProyectoController::ID_ETAPA_PENDIENTE)
                        {{-- Formulario para aceptar la propuesta --}}
                        <form action="{{ route('admin.proyectos.propuestas.review', $propuesta->clave_proyecto) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="action" value="accept">
                            <button type="submit" class="btn btn-success btn-sm" title="Aceptar Propuesta">
                                <i class="fas fa-check"></i> 
                            </button>
                        </form>
                        {{-- Botón para abrir el modal de rechazo --}}
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal" data-clave="{{ $propuesta->clave_proyecto }}" title="Rechazar Propuesta">
                            <i class="fas fa-times"></i> 
                        </button>
                    @else
                        {{-- Si la propuesta ya fue revisada, solo muestra "Revisado" --}}
                        <span class="text-muted">Revisado</span>
                    @endif
                    {{-- Enlace para generar la ficha técnica en PDF --}}
                    <a href="{{ route('admin.proyectos.ficha_tecnica_pdf', $propuesta->clave_proyecto) }}" class="btn btn-info btn-sm ml-1" title="Generar Ficha Técnica (PDF)" target="_blank">
                        <i class="fas fa-file-pdf"></i> 
                    </a>
                </td>
            </tr>
        @endforeach
    @endif
@endsection

@section('paginacion')
    {{-- Muestra la información de paginación (ej. "Mostrando 1 a 5 de 20 registros") --}}
    <div class="hint-text">
        Mostrando <b>{{ $propuestas->firstItem() }}</b> a <b>{{ $propuestas->lastItem() }}</b> de <b>{{ $propuestas->total() }}</b> registros.
    </div>
    {{-- Muestra los enlaces de paginación (Anterior, números de página, Siguiente) --}}
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

{{-- El modal para rechazar propuesta se coloca aquí, fuera de las secciones de CRUD, pero dentro del body general --}}
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
        // Inicializa el tooltip de Bootstrap si lo usas en otros botones
        $('[data-toggle="tooltip"]').tooltip();

        $('#rejectModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Botón que activó el modal
            var claveProyecto = button.data('clave'); // Extraer información de los atributos data-*
            var modal = $(this);
            modal.find('#rejectClaveProyecto').val(claveProyecto);
            // Asegúrate de que la acción del formulario apunte a la ruta correcta con la clave del proyecto
            modal.find('#rejectForm').attr('action', '{{ url("/admin/proyectos/propuestas") }}/' + claveProyecto + '/review');
            modal.find('#motivo_rechazo').val(''); // Limpiar el campo de motivo cada vez que se abre
        });
    });
</script>
@endpush