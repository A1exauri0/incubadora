<!-- resources/views/partials/edit-profile-modal.blade.php -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editProfileModalLabel">Editar mis Datos</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm" method="POST" action="{{ route('registro.datos.guardar') }}">
                    @csrf
                    <!-- Aquí se añadirán los campos específicos del rol con JavaScript -->
                    <div id="roleSpecificFields">
                        <!-- Los campos se cargarán aquí -->
                        <p class="text-center text-muted">Cargando datos...</p>
                    </div>

                    <div class="form-group text-center mt-4">
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
