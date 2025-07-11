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
    {/** @var \App\Models\User */

        $user = auth()->user();

        // Obtener todos los datos necesarios
        $etapas = DB::table('etapas')->get();
        $colores = DB::table('color')->get(); 
        $categorias = DB::table('categoria')->get(); 
        $tipos = DB::table('tipo')->get();           

        // Asignar clase de color a cada etapa
        // Esta lógica es crucial para que $etapas tenga la propiedad 'clase'
        foreach ($etapas as $etapa) {
            if ($color = $colores->firstWhere('nombre', $etapa->color)) {
                $etapa->clase = $color->clase;
            } else {
                $etapa->clase = 'secondary'; // Clase por defecto si no se encuentra el color
            }
        }

        $proyectos = collect(); // Inicializar como colección vacía por defecto

        // Definir las columnas comunes a seleccionar en todas las consultas de proyectos
        $commonSelects = [
            'proyecto.clave_proyecto',
            'proyecto.nombre',
            'proyecto.descripcion',
            'proyecto.fecha_agregado',
            'proyecto.video',
            'proyecto.categoria as categoria_id', 
            'proyecto.tipo as tipo_id',           
            'proyecto.etapa as etapa_id',         
            'categoria.nombre as nombre_categoria', // Nombre de la categoría
            'tipo.nombre as nombre_tipo',           // Nombre del tipo
            'etapas.nombre as nombre_etapa',         // Nombre de la etapa
        ];

        // Construir la consulta base con los JOINs necesarios
        $baseQuery = DB::table('proyecto')
                        ->join('categoria', 'proyecto.categoria', '=', 'categoria.idCategoria')
                        ->join('tipo', 'proyecto.tipo', '=', 'tipo.idTipo')
                        ->join('etapas', 'proyecto.etapa', '=', 'etapas.idEtapa');


        // Verificar el rol del usuario
        if ($user->hasRole('admin')) {
            // Admin ve todos los proyectos
            $proyectos = $baseQuery->select(array_merge($commonSelects, [DB::raw('0 as es_lider')]))
                                   ->get();
        } elseif ($user->hasRole('alumno')) {
            $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();

            if ($alumno) {
                $proyectos = DB::table('alumno_proyecto')
                    ->join('proyecto', 'alumno_proyecto.clave_proyecto', '=', 'proyecto.clave_proyecto')
                    ->join('categoria', 'proyecto.categoria', '=', 'categoria.idCategoria')
                    ->join('tipo', 'proyecto.tipo', '=', 'tipo.idTipo')
                    ->join('etapas', 'proyecto.etapa', '=', 'etapas.idEtapa')
                    ->where('alumno_proyecto.no_control', $alumno->no_control)
                    ->select(array_merge($commonSelects, ['alumno_proyecto.lider as es_lider']))
                    ->get();
            }
        } elseif ($user->hasRole('asesor')) {
            $asesor = DB::table('asesor')->where('correo_electronico', $user->email)->first();

            if ($asesor) {
                $proyectos = DB::table('asesor_proyecto')
                    ->join('proyecto', 'asesor_proyecto.clave_proyecto', '=', 'proyecto.clave_proyecto')
                    ->join('categoria', 'proyecto.categoria', '=', 'categoria.idCategoria')
                    ->join('tipo', 'proyecto.tipo', '=', 'tipo.idTipo')
                    ->join('etapas', 'proyecto.etapa', '=', 'etapas.idEtapa')
                    ->where('asesor_proyecto.idAsesor', $asesor->idAsesor)
                    ->select(array_merge($commonSelects, [DB::raw('0 as es_lider')]))
                    ->get();
            }
        } elseif ($user->hasRole('mentor')) {
            $mentor = DB::table('mentor')->where('correo_electronico', $user->email)->first();

            if ($mentor) {
                $proyectos = DB::table('mentor_proyecto')
                    ->join('proyecto', 'mentor_proyecto.clave_proyecto', '=', 'proyecto.clave_proyecto')
                    ->join('categoria', 'proyecto.categoria', '=', 'categoria.idCategoria')
                    ->join('tipo', 'proyecto.tipo', '=', 'tipo.idTipo')
                    ->join('etapas', 'proyecto.etapa', '=', 'etapas.idEtapa')
                    ->where('mentor_proyecto.idMentor', $mentor->idMentor)
                    ->select(array_merge($commonSelects, [DB::raw('0 as es_lider')]))
                    ->get();
            }
        }
        // Si no tiene ninguno de los roles anteriores, $proyectos seguirá siendo una colección vacía.

        // Ahora que tenemos 'etapa_id' en cada proyecto y 'clase' en cada 'etapa' de la colección $etapas
        foreach ($proyectos as $proyecto) {
            $etapaObj = $etapas->firstWhere('idEtapa', $proyecto->etapa_id);
            $proyecto->clase = $etapaObj->clase ?? 'secondary'; 
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
