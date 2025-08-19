<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\RegistroDatosController;
use Illuminate\Http\Request;
use App\Http\Controllers\{
    ParticipanteController,
    UserController,
    UsuarioController,
    AlumnoController,
    CarreraController,
    TipoController,
    CategoriaController,
    ProyectoController,
    PropuestaProyectoController,
    AsesorController,
    EtapaController,
    HabilidadController,
    MentorController,
    ServicioController,
    InicioController,
    habilidadesAMController,
    TokenController,
    NotificationController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación y verificación de correo electrónico
Auth::routes(['verify' => true]);

//Home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

//Verificacion
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Ruta para manejar la verificación del correo
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    Auth::login($request->user()->fresh());
    $request->session()->regenerate();
    return redirect()->route('email.verified')->with('status', 'email-verified');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Ruta para la vista personalizada de correo verificado
Route::get('/email/verified-successfully', function () {
    return view('auth.email-verified');
})->name('email.verified');

Route::post('email/verification-notification', function () {
    \Illuminate\Support\Facades\Notification::send(auth()->user(), new \Illuminate\Auth\Notifications\VerifyEmail);
})->middleware('auth')->name('verification.send');

// =========================================================================
// RUTAS PARA COMPLETAR EL REGISTRO DE DATOS (ACCESIBLE DESPUÉS DE VERIFICACIÓN)
// =========================================================================
Route::get('/registro-datos', [RegistroDatosController::class, 'index'])->name('registro-datos');

// Ruta para guardar o actualizar los datos (usada por el modal)
Route::post('/registro-datos', [RegistroDatosController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('registro.datos.guardar');

// NUEVA RUTA API PARA OBTENER DATOS DEL PERFIL PARA EL MODAL
Route::get('/api/user-profile-data', [RegistroDatosController::class, 'getUserProfileData'])
    ->middleware('auth') // Solo usuarios autenticados pueden pedir sus datos
    ->name('api.user.profile.data');


// =========================================================================
// RUTAS COMUNES PARA USUARIOS AUTENTICADOS Y VERIFICADOS (RESTO)
// =========================================================================
Route::middleware(['auth', 'verified'])->group(function () {
    // Rutas de Notificaciones (para administradores y asesores)
    Route::group(['middleware' => ['role:admin|asesor|alumno']], function () {
        Route::get('/notifications/unread', [NotificationController::class, 'getUnreadNotifications'])->name('notifications.unread');
        Route::post('/notifications/mark-as-read', [NotificationController::class, 'markNotificationsAsRead'])->name('notifications.markAsRead');
        // Ruta para GENERAR PDF (se mantiene en ProyectoController ya que es para cualquier proyecto)
        Route::get('/admin/proyectos/{clave_proyecto}/ficha-tecnica-pdf', [ProyectoController::class, 'generateFichaTecnicaPdf'])->name('admin.proyectos.ficha_tecnica_pdf');
    });


    // Rutas para administradores
    Route::group(['middleware' => ['role:admin']], function () {
        // El administrador ahora solo ve las propuestas que tienen Visto Bueno del asesor o están rechazadas (final)
        Route::get('/admin/proyectos/propuestas', [PropuestaProyectoController::class, 'listProposals'])->name('admin.proyectos.propuestas');
        Route::post('/admin/proyectos/propuestas/{clave_proyecto}/review', [PropuestaProyectoController::class, 'reviewProposal'])->name('admin.proyectos.propuestas.review');
    });

    // Rutas específicas para el rol de alumno
    Route::group(['middleware' => ['role:alumno']], function () {
        // Rutas para EDITAR proyectos (se mantienen en ProyectoController)
        Route::get('/proyectos/{clave_proyecto}/editar', [ProyectoController::class, 'edit'])->name('proyectos.edit');
        Route::put('/proyectos/{clave_proyecto}', [ProyectoController::class, 'update'])->name('proyectos.update');

        // Rutas para que el alumno CREE una propuesta de proyecto (ahora notifica al asesor)
        Route::get('/c_proyectos_alumno/crear', [PropuestaProyectoController::class, 'createProposalForm'])->name('proyectos.crear_propuesta');
        Route::post('/c_proyectos_alumno/store-proposal', [PropuestaProyectoController::class, 'storeProposal'])->name('proyectos.store_proposal');
    });

    // Rutas específicas para el rol de asesor (¡NUEVAS!)
    Route::group(['middleware' => ['role:asesor']], function () {
        // El asesor ve las propuestas PENDIENTES de su revisión
        Route::get('/asesor/propuestas', [PropuestaProyectoController::class, 'listAdvisorProposals'])->name('asesor.proyectos.propuestas');
        Route::post('/asesor/propuestas/{clave_proyecto}/review', [PropuestaProyectoController::class, 'reviewAdvisorProposal'])->name('asesor.proyectos.propuestas.review');

        // Rutas para que el asesor vea y gestione sus habilidades
        Route::get('/asesor/habilidades', [AsesorController::class, 'showHabilidades'])->name('asesor.habilidades.show');
        Route::post('/asesor/habilidades/store', [AsesorController::class, 'storeHabilidades'])->name('asesor.habilidades.store');
        Route::post('/asesor/habilidades/addCustom', [AsesorController::class, 'addCustomHabilidad'])->name('asesor.habilidades.addCustom');
        Route::delete('/asesor/habilidades/{id}', [AsesorController::class, 'destroyHabilidad'])->name('asesor.habilidades.destroy');
        Route::post('/asesor/habilidades/add-catalog', [AsesorController::class, 'addCatalogHabilidad'])->name('asesor.habilidades.addCatalog');

    });

    // ... (otras rutas protegidas generales si las tienes)
});

// =========================================================================
// RUTAS DE ADMINISTRACIÓN (Protegidas por middleware 'role:admin')
// =========================================================================
Route::group(['middleware' => ['role:admin']], function () {

    //CRUD Alumnos
    Route::get('/c_alumnos', [AlumnoController::class, 'index']);
    Route::post('/c_alumnos/agregar', [AlumnoController::class, 'agregar'])->name('alumnos.agregar');
    Route::post('/c_alumnos/editar', [AlumnoController::class, 'editar'])->name('alumnos.editar');
    Route::post('/c_alumnos/eliminar', [AlumnoController::class, 'eliminar'])->name('alumnos.eliminar');
    Route::post('c_alumnos/eliminarMultiple', [AlumnoController::class, 'eliminarMultiple'])->name('alumnos.eliminarMultiple');

    //CRUD asesores
    Route::get('/c_asesores', [AsesorController::class, 'index']);
    Route::post('/c_asesores/agregar', [AsesorController::class, 'agregar'])->name('asesores.agregar');
    Route::post('/c_asesores/editar', [AsesorController::class, 'editar'])->name('asesores.editar');
    Route::post('/c_asesores/eliminar', [AsesorController::class, 'eliminar'])->name('asesores.eliminar');
    Route::post('c_asesores/eliminarMultiple', [AsesorController::class, 'eliminarMultiple'])->name('asesores.eliminarMultiple');
    Route::post('c_asesores/habilidades', [AsesorController::class, 'habilidades'])->name('asesores.habilidades');

    // CRUD Carreras
    Route::get('/c_carreras', [CarreraController::class, 'index']);
    Route::post('/c_carreras/agregar', [CarreraController::class, 'agregar'])->name('carreras.agregar');
    Route::post('/c_carreras/editar', [CarreraController::class, 'editar'])->name('carreras.editar');
    Route::post('/c_carreras/eliminar', [CarreraController::class, 'eliminar'])->name('carreras.eliminar');
    Route::post('c_carreras/eliminarMultiple', [CarreraController::class, 'eliminarMultiple'])->name('carreras.eliminarMultiple');

    //CRUD Categorias
    Route::get('/c_categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::post('/c_categorias/agregar', [CategoriaController::class, 'agregar'])->name('categorias.agregar');
    Route::post('/c_categorias/editar', [CategoriaController::class, 'editar'])->name('categorias.editar');
    Route::post('/c_categorias/eliminar', [CategoriaController::class, 'eliminar'])->name('categorias.eliminar');
    Route::post('c_categorias/eliminarMultiple', [CategoriaController::class, 'eliminarMultiple'])->name('categorias.eliminarMultiple');

    //CRUD Códigos
    Route::get('/c_tokens', [TokenController::class, 'index']);
    Route::post('/c_tokens/agregar', [TokenController::class, 'agregar'])->name('tokens.agregar');
    Route::post('/c_tokens/editar', [TokenController::class, 'editar'])->name('tokens.editar');
    Route::post('/c_tokens/eliminar', [TokenController::class, 'eliminar'])->name('tokens.eliminar');
    Route::post('c_tokens/eliminarMultiple', [TokenController::class, 'eliminarMultiple'])->name('tokens.eliminarMultiple');

    //CRUD Etapas
    Route::get('/c_etapas', [EtapaController::class, 'index']);
    Route::post('/c_etapas/agregar', [EtapaController::class, 'agregar'])->name('etapas.agregar');
    Route::post('/c_etapas/editar', [EtapaController::class, 'editar'])->name('etapas.editar');
    Route::post('/c_etapas/eliminar', [EtapaController::class, 'eliminar'])->name('etapas.eliminar');
    Route::post('/c_etapas/eliminarMultiple', [EtapaController::class, 'eliminarMultiple'])->name('etapas.eliminarMultiple');

    //CRUD Habilidades
    Route::get('/c_habilidades', [HabilidadController::class, 'index']);
    Route::post('/c_habilidades/agregar', [HabilidadController::class, 'agregar'])->name('habilidades.agregar');
    Route::post('/c_habilidades/editar', [HabilidadController::class, 'editar'])->name('habilidades.editar');
    Route::post('/c_habilidades/eliminar', [HabilidadController::class, 'eliminar'])->name('habilidades.eliminar');
    Route::post('/c_habilidades/eliminarMultiple', [HabilidadController::class, 'eliminarMultiple'])->name('habilidades.eliminarMultiple');

    //CRUD mentores
    Route::get('/c_mentores', [MentorController::class, 'index']);
    Route::post('/c_mentores/agregar', [MentorController::class, 'agregar'])->name('mentores.agregar');
    Route::post('/c_mentores/editar', [MentorController::class, 'editar'])->name('mentores.editar');
    Route::post('/c_mentores/eliminar', [MentorController::class, 'eliminar'])->name('mentores.eliminar');
    Route::post('c_mentores/eliminarMultiple', [MentorController::class, 'eliminarMultiple'])->name('mentores.eliminarMultiple');

    //CRUD Participantes
    Route::get('/c_participantes', [ParticipanteController::class, 'index']);
    Route::get('/participantes/buscar', [ParticipanteController::class, 'buscarParticipante'])->name('participantes.buscar');
    Route::post('/participantes/generar-pdf', [ParticipanteController::class, 'generarPDF'])->name('participantes.generarPDF');
    Route::post('/participantes/agregar', [ParticipanteController::class, 'agregar'])->name('participantes.agregar');
    Route::post('/c_participantes/eliminar', [ParticipanteController::class, 'eliminar'])->name('participantes.eliminar');
    Route::post('c_participantes/eliminarMultiple', [ParticipanteController::class, 'eliminarMultiple'])->name('participantes.eliminarMultiple');
    Route::post('c_participantes/mostrarParticipantes', [ParticipanteController::class, 'mostrarParticipantes'])->name('participantes.mostrarParticipantes');
    Route::post('/c_participantes/actualizarLider', [ParticipanteController::class, 'actualizarLider'])->name('participantes.actualizarLider');

    //CRUD Proyectos
    Route::get('/c_proyectos', [ProyectoController::class, 'index']);
    Route::post('/c_proyectos/agregar', [ProyectoController::class, 'agregar'])->name('proyectos.agregar');
    Route::post('/c_proyectos/editar', [ProyectoController::class, 'editar'])->name('proyectos.editar');
    Route::post('/c_proyectos/eliminar', [ProyectoController::class, 'eliminar'])->name('proyectos.eliminar');
    Route::post('c_proyectos/eliminarMultiple', [ProyectoController::class, 'eliminarMultiple'])->name('proyectos.eliminarMultiple');

    //CRUD Servicios
    Route::get('/c_servicios', [ServicioController::class, 'index']);
    Route::post('/c_servicios/agregar', [ServicioController::class, 'agregar'])->name('servicios.agregar');
    Route::post('/c_servicios/editar', [ServicioController::class, 'editar'])->name('servicios.editar');
    Route::post('/c_servicios/eliminar', [ServicioController::class, 'eliminar'])->name('servicios.eliminar');
    Route::post('/c_servicios/eliminarMultiple', [ServicioController::class, 'eliminarMultiple'])->name('servicios.eliminarMultiple');

    //CRUD Tipos
    Route::get('/c_tipos', [TipoController::class, 'index']);
    Route::post('/c_tipos/agregar', [TipoController::class, 'agregar'])->name('tipos.agregar');
    Route::post('/c_tipos/editar', [TipoController::class, 'editar'])->name('tipos.editar');
    Route::post('/c_tipos/eliminar', [TipoController::class, 'eliminar'])->name('tipos.eliminar');
    Route::post('c_tipos/eliminarMultiple', [TipoController::class, 'eliminarMultiple'])->name('tipos.eliminarMultiple');

    //CRUD Usuarios
    Route::get('/c_usuarios', [UsuarioController::class, 'index']);
    Route::post('/c_usuarios/editar', [UsuarioController::class, 'editar'])->name('usuarios.editar');
    Route::post('/c_usuarios/eliminar', [UsuarioController::class, 'eliminar'])->name('usuarios.eliminar');
    Route::post('c_usuarios/eliminarMultiple', [UsuarioController::class, 'eliminarMultiple'])->name('usuarios.eliminarMultiple');

    // RUTAS PARA EL NUEVO CRUD DE ASIGNACIÓN DE HABILIDADES A ASESORES/MENTORES
    Route::prefix('c_habilidadesAM_asignar')->group(function () {
        Route::get('/', [habilidadesAMController::class, 'index'])->name('habilidadesAM.index');
        Route::post('/get-usuarios-por-tipo', [habilidadesAMController::class, 'getUsuariosPorTipo'])->name('habilidadesAM.getUsuariosPorTipo');
        Route::post('/get-habilidades-usuario', [habilidadesAMController::class, 'getHabilidadesUsuario'])->name('habilidadesAM.getHabilidadesUsuario');
        Route::post('/add-habilidad', [habilidadesAMController::class, 'addHabilidad'])->name('habilidadesAM.addHabilidad');
        Route::post('/remove-habilidad', [habilidadesAMController::class, 'removeHabilidad'])->name('habilidadesAM.removeHabilidad');
    });
});
