<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ParticipanteController extends Controller
{
    public function index(Request $request)
    {
        $clave_proyecto = $request->input('clave_proyecto');
        $queryAlumno = DB::table('alumno_proyecto')->orderBy('fecha_agregado', 'desc');
        $queryAsesor = DB::table('asesor_proyecto')->orderBy('fecha_agregado', 'desc');
        $queryMentor = DB::table('mentor_proyecto')->orderBy('fecha_agregado', 'desc');

        if ($clave_proyecto) {
            $queryAlumno->where('clave_proyecto', $clave_proyecto);
            $queryAsesor->where('clave_proyecto', $clave_proyecto);
            $queryMentor->where('clave_proyecto', $clave_proyecto);
        }

        $alumno_proyecto = $queryAlumno->get();
        $asesor_proyecto = $queryAsesor->get();
        $mentor_proyecto = $queryMentor->get();
        $proyectos = DB::table('proyecto')->orderBy('nombre')->get();
        $alumnos = DB::table('alumno')->orderBy('nombre')->get();
        $asesores = DB::table('asesor')->get();
        $mentores = DB::table('mentor')->get();
        $carreras = DB::table('carrera')->get();
        $estadisticas = DB::table('alumno')
        ->select('carrera', DB::raw('COUNT(DISTINCT no_control) as total_alumnos'))
        ->groupBy('carrera')
        ->orderBy('total_alumnos', 'desc')
        ->get();


        $titulo = "CRUD Participantes";

        return view('Admin.cruds.participantes', compact('estadisticas','alumno_proyecto', 'asesor_proyecto', 'mentor_proyecto', 'titulo', 'proyectos', 'alumnos', 'asesores', 'mentores', 'carreras', 'clave_proyecto'));
    }

    public function mostrarParticipantes(Request $request)
    {
        $claveProyecto = $request->input('clave_proyecto');
        $alumnos = DB::table('alumno_proyecto')->where('clave_proyecto', $claveProyecto)->get();
        return response()->json($alumnos);
    }


    public function buscarParticipante(Request $request)
    {
        $tipo = $request->get('tipo');
        $query = $request->get('query');

        $resultados = collect();

        if ($tipo === 'alumno') {
            $resultados = DB::table('alumno')
                ->where('nombre', 'LIKE', '%' . $query . '%')
                ->orWhere('no_control', 'LIKE', '%' . $query . '%')
                ->get();
        } elseif ($tipo === 'asesor') {
            $resultados = DB::table('asesor')
                ->where('nombre', 'LIKE', '%' . $query . '%')
                ->orWhere('idAsesor', 'LIKE', '%' . $query . '%')
                ->get();
        } elseif ($tipo === 'mentor') {
            $resultados = DB::table('mentor')
                ->where('nombre', 'LIKE', '%' . $query . '%')
                ->orWhere('idMentor', 'LIKE', '%' . $query . '%')
                ->get();
        }

        return response()->json($resultados);
    }

    public function agregar(Request $request)
    {
        $tipo = $request->input('tipo_agregar');
        $identificador = $request->input('id_agregar');
        $clave_proyecto = $request->input('clave_proyecto_agregar');

        if ($tipo === 'alumno') {

            $contador_proyectos = DB::table('alumno_proyecto')
                ->where('no_control', $identificador)
                ->count();

            // Verificar si el alumno está en 2 proyectos
            if ($contador_proyectos >= 2) {
                return back()->with('error', 'El alumno ya está registrado en 2 proyectos.');
            }

            // Verificar si el alumno ya está en ese proyecto   
            $existe = DB::table('alumno_proyecto')
                ->where('no_control', $identificador)
                ->where('clave_proyecto', $clave_proyecto)
                ->exists();

            if ($existe) {
                return back()->with('error', 'El alumno ya está registrado en este proyecto.');
            } else {
                DB::table('alumno_proyecto')->insert([
                    'no_control' => $identificador,
                    'clave_proyecto' => $clave_proyecto
                ]);
            }
        } elseif ($tipo === 'asesor') {
            $existe = DB::table('asesor_proyecto')
                ->where('idAsesor', $identificador)
                ->where('clave_proyecto', $clave_proyecto)
                ->exists();

            if ($existe) {
                return back()->with('error', 'El asesor ya está registrado en este proyecto.');
            } else {
                DB::table('asesor_proyecto')->insert([
                    'idAsesor' => $identificador,
                    'clave_proyecto' => $clave_proyecto
                ]);
            }
        } elseif ($tipo === 'mentor') {
            $existe = DB::table('mentor_proyecto')
                ->where('idMentor', $identificador)
                ->where('clave_proyecto', $clave_proyecto)
                ->exists();

            if ($existe) {
                return back()->with('error', 'El mentor ya está registrado en este proyecto.');
            } else {
                DB::table('mentor_proyecto')->insert([
                    'idMentor' => $identificador,
                    'clave_proyecto' => $clave_proyecto
                ]);
            }
        }

        return back()->with('success', 'Participante agregado correctamente.');
    }

    public function eliminar(Request $request)
    {
        $no_control = $request->input('no_control_eliminar');
        $idAsesor = $request->input('idAsesor_eliminar');
        $idMentor = $request->input('idMentor_eliminar');

        DB::table('alumno_proyecto')->where('no_control', '=', $no_control)->delete();
        DB::table('asesor_proyecto')->where('idAsesor', '=', $idAsesor)->delete();
        DB::table('mentor_proyecto')->where('idMentor', '=', $idMentor)->delete();

        return back();
    }
    public function generarPDF(Request $request)
    {
        // Obtener el clave_proyecto desde la URL
        $clave_proyecto = $request->input('clave_proyecto');
    
        // Reutilizar las consultas filtradas que tienes en el método index
        $alumno_proyecto = DB::table('alumno_proyecto')
            ->join('alumno', 'alumno_proyecto.no_control', '=', 'alumno.no_control')
            ->where('alumno_proyecto.clave_proyecto', $clave_proyecto)
            ->select('alumno_proyecto.*', 'alumno.nombre','alumno.correo_institucional','alumno.carrera','alumno.telefono','alumno.semestre')
            ->orderBy('nombre') 
            ->get();
            
        $asesor_proyecto = DB::table('asesor_proyecto')
            ->join('asesor', 'asesor_proyecto.idAsesor', '=', 'asesor.idAsesor')
            ->where('asesor_proyecto.clave_proyecto', $clave_proyecto)
            ->select('asesor_proyecto.*', 'asesor.nombre','asesor.telefono','asesor.correo_electronico')
            ->get();
    
        // Obtener el proyecto específico que se quiere exportar
        $proyecto = DB::table('proyecto')->where('clave_proyecto', $clave_proyecto)->first();
    
        // Verificar si el proyecto fue encontrado
        if (!$proyecto) {
            return redirect()->back()->with('error', 'El proyecto no fue encontrado');
        }
    
        $alumnos = DB::table('alumno')->orderBy('nombre')->get();
        $asesores = DB::table('asesor')->get();
        $carreras = DB::table('carrera')->get();
        $categorias = DB::table('categoria')->get();
        $resultados = DB::table('proyecto_resultados')
        ->where('clave_proyecto', $clave_proyecto) 
        ->orderBy('fecha_agregado') 
        ->get();
        $requerimientos = DB::table('proyecto_requerimientos')
        ->where('clave_proyecto', $clave_proyecto) 
        ->get();
        
        // Generar el PDF con los mismos datos que tienes en la vista
        $pdf = PDF::loadView('Admin.cruds.layouts.pdf', compact(
            'proyecto', 
            'alumno_proyecto', 
            'asesor_proyecto', 
            'alumnos', 
            'asesores', 
            'carreras', 
            'categorias',
            'resultados',
            'requerimientos'
        ));

        // Mostrar el PDF
        return $pdf->stream('proyecto_' . $clave_proyecto . '.pdf');
    }
    
    
}