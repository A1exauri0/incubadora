<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top">
        <div class="container-fluid">
            <a href="/home">
                <img src="{{ asset('images/logo_tec.png') }}" alt="Logo Tec" width="50" style="margin-right: 10px;">
            </a>
            <a class="navbar-brand font-weight-bold" href="/home">IncubaTec</a>

            <!-- Collapse button con Bootstrap 4 -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ml-5">
                    <li class="nav-item">
                        <a class="nav-link active" href="/home">Inicio</a>
                    </li>
                    @can('mostrar admin')
                        <!-- Dropdown Catálogos -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle font-weight-bold" href="#" id="catalogosDropdown"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Catálogos
                            </a>
                            <div class="dropdown-menu" aria-labelledby="catalogosDropdown">
                                <a class="dropdown-item" href="/c_alumnos">Alumnos</a>
                                <a class="dropdown-item" href="/c_carreras">Carreras</a>
                                <a class="dropdown-item" href="/c_categorias">Categorías</a>
                                <a class="dropdown-item" href="/c_habilidades">Habilidades</a>
                                <a class="dropdown-item" href="/c_mentores">Mentores</a>
                                <a class="dropdown-item" href="/c_servicios">Servicios</a>
                                <a class="dropdown-item" href="/c_usuarios">Usuarios</a>
                            </div>
                        </li>

                        <!-- Dropdown Proyectos (ADMIN) -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle font-weight-bold" href="#" id="proyectosDropdown"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Proyectos
                                {{-- Ícono de exclamación en el botón principal si hay revisiones --}}
                                @if (isset($revisionesAdminCount) && $revisionesAdminCount > 0)
                                    <i class="fas fa-exclamation-circle text-danger"></i>
                                @endif
                            </a>
                            <div class="dropdown-menu" aria-labelledby="proyectosDropdown">
                                <a class="dropdown-item" href="/c_proyectos">Todos</a>
                                <a class="dropdown-item" href="/c_participantes">Participantes</a>
                                <a class="dropdown-item" href="/c_tipos">Tipos</a>
                                <a class="dropdown-item" href="/c_etapas">Etapas</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('admin.proyectos.propuestas') }}">
                                    Revisar Propuestas
                                    {{-- Contador numérico dentro del dropdown --}}
                                    @if (isset($revisionesAdminCount) && $revisionesAdminCount > 0)
                                        <span
                                            class="badge rounded-pill bg-danger text-white ml-2">{{ $revisionesAdminCount }}</span>
                                    @endif
                                </a>
                            </div>
                        </li>

                        <!-- Dropdown Asesores -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle font-weight-bold" href="#" id="asesoresDropdown"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Asesores
                            </a>
                            <div class="dropdown-menu" aria-labelledby="asesoresDropdown">
                                <a class="dropdown-item" href="/c_asesores">Listado</a>
                                <a class="dropdown-item" href="/c_habilidadesAM_asignar">Asignar habilidades</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/c_tokens">Registrar usuario
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-person-fill-add" viewBox="0 0 16 16">
                                    <path
                                        d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                    <path
                                        d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                                </svg></a>

                        </li>
                    @endcan
                    @can('mostrar alumno')
                        <!-- Dropdown Proyectos Alumno -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle font-weight-bold" href="#" id="proyectosAlumnoDropdown"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Proyectos
                            </a>
                            <div class="dropdown-menu" aria-labelledby="proyectosAlumnoDropdown">
                                <a class="dropdown-item" href="{{ route('proyectos.crear_propuesta') }}">Crear Propuesta
                                    de
                                    Proyecto</a>
                            </div>
                        </li>
                    @endcan
                    @can('mostrar asesor')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle font-weight-bold" href="#"
                                id="asesorProyectosDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                Revisar Propuestas
                                {{-- Ícono de exclamación si hay propuestas por revisar --}}
                                @if (isset($revisionesAsesorCount) && $revisionesAsesorCount > 0)
                                    <i class="fas fa-exclamation-circle text-danger"></i>
                                @endif
                            </a>
                            <div class="dropdown-menu" aria-labelledby="asesorProyectosDropdown">
                                <a class="dropdown-item" href="{{ route('asesor.proyectos.propuestas') }}">
                                    Todas las propuestas
                                    @if (isset($revisionesAsesorCount) && $revisionesAsesorCount > 0)
                                        <span class="badge rounded-pill bg-danger text-white ml-2">
                                            {{ $revisionesAsesorCount }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('asesor.habilidades.show') }}">Mis habilidades</a>
                        </li>
                    @endcan
                </ul>

                <!-- Logout y Notificaciones -->
                <ul class="navbar-nav ml-auto">
                    @auth
                        {{-- Notificaciones para Admin, Asesor y Alumno --}}
                        @if (Auth::user()->hasAnyRole(['admin', 'asesor', 'alumno']))
                            <li class="nav-item dropdown mr-2">
                                <a class="nav-link" href="#" id="notificationsDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell"></i>
                                    {{-- El span siempre se renderiza, su display inicial se controla con style --}}
                                    <span class="badge badge-pill badge-danger notifications-badge"
                                        style="{{ isset($unreadNotificationsCount) && $unreadNotificationsCount > 0 ? '' : 'display: none;' }}">
                                        {{ $unreadNotificationsCount ?? 0 }}
                                    </span>
                                </a>
                                {{-- Contenido del dropdown de notificaciones --}}
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationsDropdown">
                                    <h6 class="dropdown-header">Notificaciones</h6>
                                    <div id="notifications-list">
                                        {{-- Las notificaciones se cargarán aquí con JavaScript --}}
                                        <a class="dropdown-item text-center text-muted" href="#">Cargando
                                            notificaciones...</a>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-center" href="#" id="markAllRead">Marcar todas
                                        como leídas</a>
                                </div>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{-- Mostrar icono admin si el usuario tiene el rol 'admin' --}}
                                @if (Auth::user()->hasRole('admin'))
                                    <i class="fas fa-user-tie text-white me-2" title="Administrador"></i>
                                @endif
                                {{-- Mostrar icono de usuario para el alumno --}}
                                @if (Auth::user()->hasRole('alumno'))
                                    <i class="fas fa-user-graduate text-white me-2" title="Alumno"></i>
                                @endif
                                {{-- Mostrar icono de usuario para el asesor --}}
                                @if (Auth::user()->hasRole('asesor'))
                                    <i class="fas fa-chalkboard-teacher text-white me-2" title="Asesor"></i>
                                @endif

                                <span class="ml-1">{{ Auth::user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                {{-- Nuevo enlace para "Editar mis datos" --}}
                                <a class="dropdown-item" href="#" data-toggle="modal"
                                    data-target="#editProfileModal">
                                    Editar mis datos
                                </a>
                                <div class="dropdown-divider"></div> {{-- Separador opcional --}}
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Cerrar sesión
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</header>

{{-- Script para manejar las notificaciones (sin cambios significativos, ya es genérico) --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            const notificationsList = document.getElementById('notifications-list');
            const markAllReadBtn = document.getElementById('markAllRead');
            const notificationsBadge = document.querySelector('.notifications-badge');

            // Función para actualizar el contador de notificaciones
            function updateBadgeCount(count) {
                if (notificationsBadge) {
                    notificationsBadge.textContent = count;
                    if (count > 0) {
                        notificationsBadge.style.display = 'inline-block';
                    } else {
                        notificationsBadge.style.display = 'none';
                    }
                }
            }

            // Función para cargar las notificaciones y actualizar el badge
            function loadNotifications() {
                notificationsList.innerHTML =
                    '<a class="dropdown-item text-center text-muted" href="#">Cargando notificaciones...</a>';

                fetch('{{ route('notifications.unread') }}', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error('Network response was not ok. Status: ' + response
                                    .status + ' - ' + text);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        notificationsList.innerHTML = ''; // Limpiar el mensaje de carga

                        // Actualizar el contador con el número real de notificaciones sin leer
                        updateBadgeCount(data.length);

                        if (data.length === 0) {
                            notificationsList.innerHTML =
                                '<a class="dropdown-item text-center text-muted" href="#">No hay notificaciones</a>';
                        } else {
                            data.forEach(notification => {
                                const notificationItem = document.createElement('a');
                                notificationItem.classList.add('dropdown-item');
                                notificationItem.href = notification.link;

                                let notificationHtml = `<div>${notification.message}</div>`;
                                notificationHtml +=
                                    `<small class="text-muted">${notification.time}</small>`;

                                notificationItem.innerHTML = notificationHtml;

                                notificationItem.addEventListener('click', function(e) {
                                    // Marcar como leída y luego recargar solo el badge, no la lista completa
                                    markNotificationAsRead(notification.id);
                                    // No es necesario loadNotifications() aquí, ya que markNotificationAsRead lo hará
                                });
                                notificationsList.appendChild(notificationItem);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar notificaciones:', error);
                        notificationsList.innerHTML =
                            '<a class="dropdown-item text-center text-danger" href="#">Error al cargar notificaciones.</a>';
                        updateBadgeCount(0); // Asegurarse de que el badge se oculte en caso de error
                    });
            }

            // Función para marcar como leída y luego actualizar el contador
            function markNotificationAsRead(notificationId = null) {
                fetch('{{ route('notifications.markAsRead') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            id: notificationId
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error('Error: ' + response.status + ' - ' + text);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            updateBadgeCount(data.unread_count);
                            loadNotifications();
                        }
                    })
                    .catch(error => {
                        console.error('Error al marcar notificaciones como leídas:', error);
                    });
            }

            if (notificationsDropdown) {
                notificationsDropdown.addEventListener('click', function(e) {
                    // Cargar notificaciones cuando se abre el dropdown
                    loadNotifications();
                });
            }

            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    markNotificationAsRead(); // Marcar todas como leídas
                });
            }

            // Llama a loadNotifications al cargar la página para actualizar el contador inicial
            loadNotifications();
        });
    </script>
@endpush
