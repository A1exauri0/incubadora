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

        $proyectos = collect(); // Inicializar como colección vacía por defecto

        // Verificar el rol del usuario
        if ($user->hasRole('admin')) {
            // Admin ve todos los proyectos
            $proyectos = DB::table('proyecto')
                         ->select('proyecto.*', DB::raw('0 as es_lider')) // Los admins no son líderes de proyectos específicos en esta vista
                         ->get();
        } elseif ($user->hasRole('alumno')) {
            // Obtener no_control del alumno por su correo institucional
            $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();

            if ($alumno) {
                // Modificación aquí: Incluir el campo 'lider' de alumno_proyecto como 'es_lider'
                $proyectos = DB::table('proyecto')
                    ->join('alumno_proyecto', 'proyecto.clave_proyecto', '=', 'alumno_proyecto.clave_proyecto')
                    ->where('alumno_proyecto.no_control', $alumno->no_control)
                    ->select('proyecto.*', 'alumno_proyecto.lider as es_lider') // Añadido 'lider as es_lider'
                    ->get();
            }
        } elseif ($user->hasRole('asesor')) {
            // Obtener idAsesor del asesor por su correo electrónico
            $asesor = DB::table('asesor')->where('correo_electronico', $user->email)->first();

            if ($asesor) {
                $proyectos = DB::table('proyecto')
                    ->join('asesor_proyecto', 'proyecto.clave_proyecto', '=', 'asesor_proyecto.clave_proyecto')
                    ->where('asesor_proyecto.idAsesor', $asesor->idAsesor)
                    ->select('proyecto.*', DB::raw('0 as es_lider')) // Los asesores no son líderes
                    ->get();
            }
        } elseif ($user->hasRole('mentor')) {
            // Obtener idMentor del mentor por su correo electrónico
            $mentor = DB::table('mentor')->where('correo_electronico', $user->email)->first();

            if ($mentor) {
                $proyectos = DB::table('proyecto')
                    ->join('mentor_proyecto', 'proyecto.clave_proyecto', '=', 'mentor_proyecto.clave_proyecto')
                    ->where('mentor_proyecto.idMentor', $mentor->idMentor)
                    ->select('proyecto.*', DB::raw('0 as es_lider')) // Los mentores no son líderes
                    ->get();
            }
        }
        // Si no tiene ninguno de los roles anteriores, $proyectos seguirá siendo una colección vacía.

        // Asignar clase a cada proyecto según su etapa (solo si hay proyectos)
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
