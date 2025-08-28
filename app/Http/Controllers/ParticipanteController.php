<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Importar Auth
use App\Models\User; // Importar el modelo User
use Barryvdh\DomPDF\Facade\Pdf;

class ParticipanteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Este método parece ser para un CRUD general de participantes en Admin.
        // Se mantiene como estaba, asumiendo que es para una vista de administrador separada.
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

        return view('Admin.cruds.participantes', compact('estadisticas', 'alumno_proyecto', 'asesor_proyecto', 'mentor_proyecto', 'titulo', 'proyectos', 'alumnos', 'asesores', 'mentores', 'carreras', 'clave_proyecto'));
    }

    public function mostrarParticipantes(Request $request)
    {
        // Este método parece ser para una petición AJAX en el admin CRUD, no directamente para la vista de proyecto.
        $claveProyecto = $request->input('clave_proyecto');
        $alumnos = DB::table('alumno_proyecto')->where('clave_proyecto', $claveProyecto)->get();
        return response()->json($alumnos);
    }

    // Este es el método que carga la vista de participantes de UN proyecto específico
    public function mostrarProyecto($clave_proyecto)
    {
        /** @var \App\Models\User */
        $user = Auth::user();

        // Obtener el proyecto
        $proyecto = DB::table('proyecto')->where('clave_proyecto', $clave_proyecto)->first();

        if (!$proyecto) {
            return redirect()->route('home')->with('error', 'Proyecto no encontrado.');
        }

        // Determinar si el usuario actual es el líder del proyecto
        $esLider = false;
        $esAlumnoParticipante = false;
        if ($user->hasRole('alumno')) {
            $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();
            if ($alumno) {
                $liderCheck = DB::table('alumno_proyecto')
                                ->where('clave_proyecto', $clave_proyecto)
                                ->where('no_control', $alumno->no_control)
                                ->where('lider', 1)
                                ->first();
                if ($liderCheck) {
                    $esLider = true;
                }
                // Nuevo: verificar si el alumno participa en el proyecto
                $participaCheck = DB::table('alumno_proyecto')
                                ->where('clave_proyecto', $clave_proyecto)
                                ->where('no_control', $alumno->no_control)
                                ->exists();
                if ($participaCheck) {
                    $esAlumnoParticipante = true;
                }
            }
        }

        // Verificar si el usuario es un asesor asignado al proyecto
        $esAsesorDelProyecto = false;
        if ($user->hasRole('asesor')) {
            $asesor = DB::table('asesor')->where('correo_electronico', $user->email)->first();
            if ($asesor) {
                $esAsesorDelProyecto = DB::table('asesor_proyecto')
                                ->where('clave_proyecto', $clave_proyecto)
                                ->where('idAsesor', $asesor->idAsesor)
                                ->exists();
            }
        }


        // Solo permitir acceso si es admin, el líder del proyecto, un asesor asignado o un alumno participante
        if (!$user->hasRole('admin') && !$esLider && !$esAsesorDelProyecto && !$esAlumnoParticipante) {
            return redirect()->route('home')->with('error', 'No tienes permiso para ver los participantes de este proyecto.');
        }

        // Obtener alumnos asignados al proyecto
        $alumnos_proyecto = DB::table('alumno_proyecto')
            ->where('alumno_proyecto.clave_proyecto', $clave_proyecto)
            ->join('alumno', 'alumno_proyecto.no_control', '=', 'alumno.no_control')
            ->select('alumno.no_control', 'alumno.nombre', 'alumno.carrera', 'alumno.correo_institucional', 'alumno_proyecto.lider')
            ->get();

        // Obtener asesores asignados al proyecto
        $asesores_proyecto = DB::table('asesor_proyecto')
            ->where('asesor_proyecto.clave_proyecto', $clave_proyecto)
            ->join('asesor', 'asesor_proyecto.idAsesor', '=', 'asesor.idAsesor')
            ->select('asesor.idAsesor', 'asesor.nombre', 'asesor.correo_electronico')
            ->get();

        $titulo = "Participantes del Proyecto";

        return view('alumnos.participantes', compact('proyecto', 'alumnos_proyecto', 'asesores_proyecto', 'titulo', 'esLider'));
    }

    // Método para buscar participantes (genérico para el CRUD Admin, no para los modales)
    public function buscarParticipante(Request $request)
    {
        $tipo = $request->get('tipo');
        $query = $request->get('query');

        $resultados = collect();

        if ($tipo === 'alumno') {
            $resultados = DB::table('alumno')
                ->where('nombre', 'LIKE', '%' . $query . '%')
                ->orWhere('no_control', 'LIKE', '%' . $query . '%')
                ->select(
                    'no_control as id',
                    'nombre'
                )
                ->get();

            foreach ($resultados as $alumno) {
                $proyectos = DB::table('alumno_proyecto')
                    ->join('proyecto', 'alumno_proyecto.clave_proyecto', '=', 'proyecto.clave_proyecto')
                    ->where('alumno_proyecto.no_control', $alumno->id)
                    ->pluck('proyecto.nombre')
                    ->implode(', ');
                $alumno->proyectos = $proyectos ?: 'Sin proyectos';
            }
        } elseif ($tipo === 'asesor') {
            $resultados = DB::table('asesor')
                ->where('nombre', 'LIKE', '%' . $query . '%')
                ->orWhere('idAsesor', 'LIKE', '%' . $query . '%')
                ->select(
                    'idAsesor as id',
                    'nombre'
                )
                ->get();

            foreach ($resultados as $asesor) {
                $proyectos = DB::table('asesor_proyecto')
                    ->join('proyecto', 'asesor_proyecto.clave_proyecto', '=', 'proyecto.clave_proyecto')
                    ->where('asesor_proyecto.idAsesor', $asesor->id)
                    ->pluck('proyecto.nombre')
                    ->implode(', ');
                $asesor->proyectos = $proyectos ?: 'Sin proyectos';
            }
        } elseif ($tipo === 'mentor') {
            $resultados = DB::table('mentor')
                ->where('nombre', 'LIKE', '%' . $query . '%')
                ->orWhere('idMentor', 'LIKE', '%' . $query . '%')
                ->select(
                    'idMentor as id',
                    'nombre'
                )
                ->get();

            foreach ($resultados as $mentor) {
                $proyectos = DB::table('mentor_proyecto')
                    ->join('proyecto', 'mentor_proyecto.clave_proyecto', '=', 'proyecto.clave_proyecto')
                    ->where('mentor_proyecto.idMentor', $mentor->id)
                    ->pluck('proyecto.nombre')
                    ->implode(', ');
                $mentor->proyectos = $proyectos ?: 'Sin proyectos';
            }
        }

        return response()->json($resultados);
    }

    // Método para buscar alumnos por número de control, correo o nombre (para modal AJAX)
    public function searchAlumnos(Request $request)
    {
        $query = $request->input('query');
        $clave_proyecto = $request->input('clave_proyecto');

        $alumnos = DB::table('alumno')
            ->where(function ($q) use ($query) {
                $q->where('no_control', 'LIKE', '%' . $query . '%')
                  ->orWhere('correo_institucional', 'LIKE', '%' . $query . '%')
                  ->orWhere('nombre', 'LIKE', '%' . $query . '%');
            })
            // Excluir alumnos que ya están en el proyecto
            ->whereNotIn('no_control', function ($q) use ($clave_proyecto) {
                $q->select('no_control')->from('alumno_proyecto')->where('clave_proyecto', $clave_proyecto);
            })
            ->get(['no_control', 'nombre', 'carrera', 'correo_institucional']);

        return response()->json(['alumnos' => $alumnos]);
    }

    // MODIFICADO: Método para buscar asesores por correo o nombre (para modal AJAX)
    public function searchAsesores(Request $request)
    {
        $query = $request->input('query');
        $clave_proyecto = $request->input('clave_proyecto');

        $asesores = DB::table('asesor')
            ->where(function ($q) use ($query) {
                // Ahora busca por nombre o correo
                $q->where('nombre', 'LIKE', '%' . $query . '%')
                  ->orWhere('correo_electronico', 'LIKE', '%' . $query . '%');
            })
            // Excluir asesores que ya están en el proyecto
            ->whereNotIn('idAsesor', function ($q) use ($clave_proyecto) {
                $q->select('idAsesor')->from('asesor_proyecto')->where('clave_proyecto', $clave_proyecto);
            })
            ->get(['idAsesor', 'nombre', 'correo_electronico']);

        // Adjuntar habilidades a cada asesor
        foreach ($asesores as $asesor) {
            $habilidades = DB::table('habilidad_asesor')
                ->join('habilidad', 'habilidad_asesor.idHabilidad', '=', 'habilidad.idHabilidad')
                ->where('habilidad_asesor.idAsesor', $asesor->idAsesor)
                ->pluck('habilidad.nombre') // <<--- ¡CORRECCIÓN AQUÍ!
                ->toArray();
            $asesor->habilidades = $habilidades;
        }

        return response()->json(['asesores' => $asesores]);
    }

    // Método para agregar un alumno a un proyecto (llamado desde el modal)
    public function agregarAlumno(Request $request)
    {
        $request->validate([
            'no_control_alumno' => 'required|string|exists:alumno,no_control',
            'clave_proyecto' => 'required|string|exists:proyecto,clave_proyecto',
        ]);

        /** @var \App\Models\User */
        $user = Auth::user();

        // Verificar si el usuario autenticado es admin o líder del proyecto
        $esLider = false;
        if ($user->hasRole('alumno')) {
            $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();
            if ($alumno) {
                $liderCheck = DB::table('alumno_proyecto')
                                ->where('clave_proyecto', $request->clave_proyecto)
                                ->where('no_control', $alumno->no_control)
                                ->where('lider', 1)
                                ->first();
                if ($liderCheck) {
                    $esLider = true;
                }
            }
        }

        if (!$user->hasRole('admin') && !$esLider) {
            return back()->with('error', 'No tienes permiso para agregar alumnos a este proyecto.');
        }

        try {
            // Verificar si el alumno ya está en el proyecto
            $exists = DB::table('alumno_proyecto')
                        ->where('no_control', $request->no_control_alumno)
                        ->where('clave_proyecto', $request->clave_proyecto)
                        ->exists();

            if ($exists) {
                return back()->with('error', 'El alumno ya es parte de este proyecto.');
            }

            // Verificar si el alumno está en 2 proyectos
            $contador_proyectos = DB::table('alumno_proyecto')
                ->where('no_control', $request->no_control_alumno)
                ->count();

            if ($contador_proyectos >= 2) {
                return back()->with('error', 'El alumno ya está registrado en 2 proyectos.');
            }

            DB::table('alumno_proyecto')->insert([
                'no_control' => $request->no_control_alumno,
                'clave_proyecto' => $request->clave_proyecto,
                'lider' => 0, // Por defecto no es líder
                'fecha_agregado' => now(),
            ]);

            return back()->with('success', 'Alumno agregado exitosamente al proyecto.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al agregar alumno: ' . $e->getMessage());
        }
    }

    // Método para agregar un asesor a un proyecto (llamado desde el modal)
    public function agregarAsesor(Request $request)
    {
        $request->validate([
            'id_asesor' => 'required|integer|exists:asesor,idAsesor',
            'clave_proyecto' => 'required|string|exists:proyecto,clave_proyecto',
        ]);

        /** @var \App\Models\User */
        $user = Auth::user();

        // Verificar si el usuario autenticado es admin o líder del proyecto
        $esLider = false;
        if ($user->hasRole('alumno')) {
            $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();
            if ($alumno) {
                $liderCheck = DB::table('alumno_proyecto')
                                ->where('clave_proyecto', $request->clave_proyecto)
                                ->where('no_control', $alumno->no_control)
                                ->where('lider', 1)
                                ->first();
                if ($liderCheck) {
                    $esLider = true;
                }
            }
        }

        if (!$user->hasRole('admin') && !$esLider) {
            return back()->with('error', 'No tienes permiso para agregar asesores a este proyecto.');
        }

        try {
            // Verificar si el asesor ya está en el proyecto
            $exists = DB::table('asesor_proyecto')
                        ->where('idAsesor', $request->id_asesor)
                        ->where('clave_proyecto', $request->clave_proyecto)
                        ->exists();

            if ($exists) {
                return back()->with('error', 'El asesor ya es parte de este proyecto.');
            }

            DB::table('asesor_proyecto')->insert([
                'idAsesor' => $request->id_asesor,
                'clave_proyecto' => $request->clave_proyecto,
                'fecha_agregado' => now(),
            ]);

            return back()->with('success', 'Asesor agregado exitosamente al proyecto.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al agregar asesor: ' . $e->getMessage());
        }
    }

    // Método para eliminar un alumno del proyecto
    public function eliminarAlumno(Request $request)
    {
        $request->validate([
            'no_control_eliminar' => 'required|string',
            'clave_proyecto_eliminar' => 'required|string',
        ]);

        /** @var \App\Models\User */
        $user = Auth::user();

        // Verificar si el usuario autenticado es admin o líder del proyecto
        $esLider = false;
        if ($user->hasRole('alumno')) {
            $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();
            if ($alumno) {
                $liderCheck = DB::table('alumno_proyecto')
                                ->where('clave_proyecto', $request->clave_proyecto_eliminar)
                                ->where('no_control', $alumno->no_control)
                                ->where('lider', 1)
                                ->first();
                if ($liderCheck) {
                    $esLider = true;
                }
            }
        }

        if (!$user->hasRole('admin') && !$esLider) {
            return back()->with('error', 'No tienes permiso para eliminar alumnos de este proyecto.');
        }

        try {
            DB::table('alumno_proyecto')
                ->where('no_control', $request->no_control_eliminar)
                ->where('clave_proyecto', $request->clave_proyecto_eliminar)
                ->delete();

            return back()->with('success', 'Alumno eliminado del proyecto correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar alumno: ' . $e->getMessage());
        }
    }

    // Método para eliminar un asesor del proyecto
    public function eliminarAsesor(Request $request)
    {
        $request->validate([
            'idAsesor_eliminar' => 'required|integer',
            'clave_proyecto_eliminar' => 'required|string',
        ]);

        /** @var \App\Models\User */
        $user = Auth::user();

        // Verificar si el usuario autenticado es admin o líder del proyecto
        $esLider = false;
        if ($user->hasRole('alumno')) {
            $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();
            if ($alumno) {
                $liderCheck = DB::table('alumno_proyecto')
                                ->where('clave_proyecto', $request->clave_proyecto_eliminar)
                                ->where('no_control', $alumno->no_control)
                                ->where('lider', 1)
                                ->first();
                if ($liderCheck) {
                    $esLider = true;
                }
            }
        }

        if (!$user->hasRole('admin') && !$esLider) {
            return back()->with('error', 'No tienes permiso para eliminar asesores de este proyecto.');
        }

        try {
            DB::table('asesor_proyecto')
                ->where('idAsesor', $request->idAsesor_eliminar)
                ->where('clave_proyecto', $request->clave_proyecto_eliminar)
                ->delete();

            return back()->with('success', 'Asesor eliminado del proyecto correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar asesor: ' . $e->getMessage());
        }
    }

    // Método para generar el PDF (se mantiene igual)
    public function generarPDF(Request $request)
    {
        $clave_proyecto = $request->input('clave_proyecto');
        $alumno_proyecto = DB::table('alumno_proyecto')
            ->join('alumno', 'alumno_proyecto.no_control', '=', 'alumno.no_control')
            ->where('alumno_proyecto.clave_proyecto', $clave_proyecto)
            ->select('alumno_proyecto.*', 'alumno.nombre', 'alumno.correo_institucional', 'alumno.carrera', 'alumno.telefono', 'alumno.semestre')
            ->orderBy('nombre')
            ->get();

        $asesor_proyecto = DB::table('asesor_proyecto')
            ->join('asesor', 'asesor_proyecto.idAsesor', '=', 'asesor.idAsesor')
            ->where('asesor_proyecto.clave_proyecto', $clave_proyecto)
            ->select('asesor_proyecto.*', 'asesor.nombre', 'asesor.telefono', 'asesor.correo_electronico')
            ->get();

        $proyecto = DB::table('proyecto')->where('clave_proyecto', $clave_proyecto)->first();
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

        $pdf = PDF::loadView('layouts.pdf', compact(
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

        return $pdf->stream('proyecto_' . $clave_proyecto . '.pdf');
    }

    // El método 'agregar(Request $request)' genérico para el CRUD Admin se mantiene.
    public function agregar(Request $request)
    {
        $tipo = $request->input('tipo_agregar');
        $identificador = $request->input('id_agregar');
        $clave_proyecto = $request->input('clave_proyecto_agregar');

        if ($tipo === 'alumno') {
            $contador_proyectos = DB::table('alumno_proyecto')
                ->where('no_control', $identificador)
                ->count();

            if ($contador_proyectos >= 2) {
                return back()->with('error', 'El alumno ya está registrado en 2 proyectos.');
            }

            $existe = DB::table('alumno_proyecto')
                ->where('no_control', $identificador)
                ->where('clave_proyecto', $clave_proyecto)
                ->exists();

            if ($existe) {
                return back()->with('error', 'El alumno ya está registrado en este proyecto.');
            } else {
                DB::table('alumno_proyecto')->insert([
                    'no_control' => $identificador,
                    'clave_proyecto' => $clave_proyecto,
                    'lider' => 0, // Por defecto no es líder en el CRUD admin
                    'fecha_agregado' => now(),
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
                    'clave_proyecto' => $clave_proyecto,
                    'fecha_agregado' => now(),
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
                    'clave_proyecto' => $clave_proyecto,
                    'fecha_agregado' => now(),
                ]);
            }
        }

        return back()->with('success', 'Participante agregado correctamente.');
    }

    // El método 'eliminar(Request $request)' genérico para el CRUD Admin se mantiene.
    public function eliminar(Request $request)
    {
        $no_control = $request->input('no_control_eliminar');
        $idAsesor = $request->input('idAsesor_eliminar');
        $idMentor = $request->input('idMentor_eliminar');
        $clave_proyecto = $request->input('clave_proyecto_eliminar');

        if ($no_control) {
            DB::table('alumno_proyecto')
                ->where('no_control', $no_control)
                ->where('clave_proyecto', $clave_proyecto)
                ->delete();
        }

        if ($idAsesor) {
            DB::table('asesor_proyecto')
                ->where('idAsesor', $idAsesor)
                ->where('clave_proyecto', $clave_proyecto)
                ->delete();
        }

        if ($idMentor) {
            DB::table('mentor_proyecto')
                ->where('idMentor', $idMentor)
                ->where('clave_proyecto', $clave_proyecto)
                ->delete();
        }

        return back()->with('success', 'Participante eliminado del proyecto correctamente.');
    }
}
