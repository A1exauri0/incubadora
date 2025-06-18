<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

public function index()
{
    $user = auth()->user();

    // Obtener todos los datos necesarios
    $etapas = DB::table('etapas')->get();
    $colores = DB::table('color')->get();
    $categorias = DB::table('categoria')->get();
    $tipos = DB::table('tipo')->get();

    // Asignar clase de color a cada etapa
    foreach ($etapas as $etapa) {
        if ($color = $colores->firstWhere('nombre', $etapa->color)) {
            $etapa->clase = $color->clase;
        }
    }

    // Verificar si es admin o alumno
    if ($user->hasRole('admin')) {
        // Admin ve todos los proyectos
        $proyectos = DB::table('proyecto')->get();
    } elseif ($user->hasRole('alumno')) {
        // Obtener no_control del alumno por su correo institucional
        $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();

        if ($alumno) {
            $proyectos = DB::table('proyecto')
                ->join('alumno_proyecto', 'proyecto.clave_proyecto', '=', 'alumno_proyecto.clave_proyecto')
                ->where('alumno_proyecto.no_control', $alumno->no_control)
                ->select('proyecto.*')
                ->get();
        } else {
            $proyectos = collect(); // vacío si no se encuentra el alumno
        }
    } else {
        $proyectos = collect(); // vacío por seguridad si no es admin ni alumno
    }

    // Asignar clase a cada proyecto según su etapa
    foreach ($proyectos as $proyecto) {
        $etapa = $etapas->firstWhere('idEtapa', $proyecto->etapa);
        $proyecto->clase = $etapa->clase ?? 'default-class';
    }

    return view('home', [
        'titulo' => 'Inicio',
        'proyectos' => $proyectos,
        'categorias' => $categorias,
        'tipos' => $tipos,
        'etapas' => $etapas,
    ]);
}

}
