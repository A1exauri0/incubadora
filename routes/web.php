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
    // Rutas protegidas
    Route::get('/c_carreras', [CarreraController::class, 'index']);

});


Route::middleware(['admin'])->group(function () {

    // CRUD Carreras
    Route::post('/c_carreras/agregar', [CarreraController::class, 'agregar'])->name('carreras.agregar');
    Route::post('/c_carreras/editar', [CarreraController::class, 'editar'])->name('carreras.editar');
    Route::post('/c_carreras/eliminar', [CarreraController::class, 'eliminar'])->name('carreras.eliminar');
    Route::post('c_carreras/eliminarMultiple', [CarreraController::class, 'eliminarMultiple'])->name('carreras.eliminarMultiple');
});
