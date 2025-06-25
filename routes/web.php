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
    AsesorController,
    EtapaController,
    HabilidadController,
    MentorController,
    ServicioController,
    InicioController,
    habilidadesAMController, // NUEVO: Asegúrate de que el nombre de la clase sea exactamente 'habilidadesAMController'
    TokenController
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

    // Redirigir a la página personalizada de confirmación
    return redirect()->route('email.confirmed');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Ruta para la vista personalizada de correo verificado
Route::get('/email-verified', function () {
    return view('auth.email-verified');
})->name('email.confirmed');

Route::post('email/verification-notification', function () {
    \Illuminate\Support\Facades\Notification::send(auth()->user(), new \Illuminate\Auth\Notifications\VerifyEmail);
})->middleware('auth')->name('verification.send');

Route::group(['middleware' => ['role:admin']], function () {
    /*
    Route::prefix('c_alumnos')->group(function () {
    Route::get('/', [AlumnoController::class, 'index']);
    Route::post('/agregar', [AlumnoController::class, 'agregar'])->name('alumnos.agregar');
    Route::post('/editar', [AlumnoController::class, 'editar'])->name('alumnos.editar');
    Route::post('/eliminar', [AlumnoController::class, 'eliminar'])->name('alumnos.eliminar');
    Route::post('/eliminarMultiple', [AlumnoController::class, 'eliminarMultiple'])->name('alumnos.eliminarMultiple');
});
 */

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
    route::get('/participantes/buscar', [ParticipanteController::class, 'buscarParticipante'])->name('participantes.buscar');
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
    // Route::post('/c_usuarios/agregar', [UsuarioController::class, 'agregar'])->name('usuarios.agregar'); // Comentada o eliminada si no se usa
    Route::post('/c_usuarios/editar', [UsuarioController::class, 'editar'])->name('usuarios.editar');
    Route::post('/c_usuarios/eliminar', [UsuarioController::class, 'eliminar'])->name('usuarios.eliminar');
    Route::post('c_usuarios/eliminarMultiple', [UsuarioController::class, 'eliminarMultiple'])->name('usuarios.eliminarMultiple');

    // RUTAS PARA EL NUEVO CRUD DE ASIGNACIÓN DE HABILIDADES A ASESORES/MENTORES
    // Ojo: Asegúrate de que el controlador se llama 'habilidadesAMController' en el archivo App\Http\Controllers
    Route::prefix('c_habilidadesAM_asignar')->group(function () { // Cambiado el prefijo para evitar colisión con c_habilidades
        Route::get('/', [habilidadesAMController::class, 'index'])->name('habilidadesAM.index');
        Route::post('/get-usuarios-por-tipo', [habilidadesAMController::class, 'getUsuariosPorTipo'])->name('habilidadesAM.getUsuariosPorTipo');
        Route::post('/get-habilidades-usuario', [habilidadesAMController::class, 'getHabilidadesUsuario'])->name('habilidadesAM.getHabilidadesUsuario');
        Route::post('/add-habilidad', [habilidadesAMController::class, 'addHabilidad'])->name('habilidadesAM.addHabilidad');
        Route::post('/remove-habilidad', [habilidadesAMController::class, 'removeHabilidad'])->name('habilidadesAM.removeHabilidad');
    });

    // Estas rutas estaban previamente en tu código, pero apuntaban al controlador incorrecto
    // o tenían nombres de ruta que no concordaban con las acciones.
    // Las he comentado/eliminado para evitar conflictos y que se usen las nuevas rutas AJAX.
    // Route::get('/c_habilidadesAM', [habilidadesAMController::class, 'index']);
    // Route::post('/c_habilidadesAM/agregar', [habilidadesAMController::class, 'agregar'])->name('habilidadesAM.agregar');
    // Route::post('/c_habilidadesAM/editar', [habilidadesAMController::class,'editar'])->name('habilidadesAM.editar');
    // Route::post('/c_habilidadesAM/eliminar', [HabilidadesAMController::class,'eliminar'])->name('habilidadesAM.editar');
    // Route::post('/c_habilidadesAM/eliminarMultiple', [HabilidadesAMController::class,'eliminarMultiple'])->name('habilidadesAM.eliminarMultiple');
});


Route::group(['middleware' => ['role:alumno']], function () {
    Route::get('/registro-datos', [RegistroDatosController::class, 'index'])->name('registro-datos');
    Route::post('/registro-datos', [RegistroDatosController::class, 'store'])
        ->middleware(['auth', 'verified'])
        ->name('registro.datos.guardar');
});