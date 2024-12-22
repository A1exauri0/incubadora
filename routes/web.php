<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ParticipanteController,
    UserController,
    MainController,
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
    habilidadesAMController
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


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
});
