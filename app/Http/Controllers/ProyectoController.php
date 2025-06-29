<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Asegúrate de que esta línea esté presente

class ProyectoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Asegura que el usuario esté autenticado para todos los métodos
    }

    /**
     * Muestra la vista principal del CRUD de proyectos (probablemente para administradores).
     */
    public function index()
    {
        $proyectos = DB::table('proyecto')->orderBy('fecha_agregado', 'desc')->get();
        $columnas = ['Clave', 'Nombre', 'Nombre descriptivo', 'Categoria', 'Tipo', 'Etapa', 'Video', 'Registrado'];
        $categorias = DB::table('categoria')->get();
        $alumnos = DB::table('alumno')->get(); // Mantengo si se usa, si no, puedes quitarlo.
        $tipos = DB::table('tipo')->get();
        $etapas = DB::table('etapas')->get();
        $total_registros = $proyectos->count();

        $titulo = "CRUD Proyectos";

        return view('Admin.cruds.proyectos', compact('proyectos', 'titulo', 'columnas', 'total_registros', 'categorias', 'alumnos', 'tipos', 'etapas'));
    }

    /**
     * Verifica si un proyecto con la clave dada ya existe.
     */
    public function registro_existe($clave_proyecto)
    {
        $existe = DB::table('proyecto')->where('clave_proyecto', '=', $clave_proyecto)->count();
        return $existe > 0;
    }

    /**
     * Agrega un nuevo proyecto (usado en el CRUD de administración).
     */
    public function agregar(Request $request)
    {
        $clave_proyecto = $request->input('clave_proyecto_agregar');
        $nombre = $request->input('nombre_agregar');
        $nombre_descriptivo = $request->input('nombre_descriptivo_agregar');
        $descripcion = $request->input('descripcion_agregar');
        $categoria = $request->input('categoria_agregar');
        $tipo = $request->input('tipo_agregar');
        $etapa = $request->input('etapa_agregar');
        $video = $request->input('video_agregar');
        $area_aplicacion = $request->input('area_aplicacion_agregar');
        $naturaleza_tecnica = $request->input('naturaleza_tecnica_agregar');
        $objetivo = $request->input('objetivo_agregar');

        if ($this->registro_existe($clave_proyecto)) {
            return back()->with('error', 'La clave del proyecto que intentó agregar ya existe en otro registro.');
        } else {
            DB::table('proyecto')->insert([
                'clave_proyecto' => $clave_proyecto,
                'nombre' => $nombre,
                'nombre_descriptivo' => $nombre_descriptivo,
                'descripcion' => $descripcion,
                'categoria' => $categoria,
                'tipo' => $tipo,
                'etapa' => $etapa,
                'video' => $video,
                'area_aplicacion' => $area_aplicacion,
                'naturaleza_tecnica' => $naturaleza_tecnica,
                'objetivo' => $objetivo
            ]);
        }
        return back()->with('success', 'Proyecto agregado exitosamente.');
    }

    /**
     * Actualiza un proyecto desde la interfaz de administración (tu método 'editar' renombrado).
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminUpdate(Request $request) // Renombrado de 'editar'
    {
        $clave_proyecto = $request->input('clave_proyecto_editar'); // Clave original del proyecto a editar
        $clave_proyecto_mod = $request->input('clave_proyecto_mod'); // Nueva clave (si se modificó)
        $nombre = $request->input('nombre_mod');
        $nombre_descriptivo = $request->input('nombre_descriptivo_mod');
        $descripcion = $request->input('descripcion_mod');
        $categoria = $request->input('categoria_mod');
        $tipo = $request->input('tipo_mod');
        $etapa = $request->input('etapa_mod');
        $video = $request->input('video_mod');
        $area_aplicacion = $request->input('area_aplicacion_mod');
        $naturaleza_tecnica = $request->input('naturaleza_tecnica_mod');
        $objetivo = $request->input('objetivo_mod');

        if ($clave_proyecto != $clave_proyecto_mod && $this->registro_existe($clave_proyecto_mod)) {
            return back()->with('error', 'La clave del proyecto que intentó modificar ya existe en otro registro.');
        } else {
            DB::table('proyecto')->where('clave_proyecto', '=', $clave_proyecto)->update([
                'clave_proyecto' => $clave_proyecto_mod,
                'nombre' => $nombre,
                'nombre_descriptivo' => $nombre_descriptivo,
                'descripcion' => $descripcion,
                'categoria' => $categoria,
                'tipo' => $tipo,
                'etapa' => $etapa,
                'video' => $video,
                'area_aplicacion' => $area_aplicacion,
                'naturaleza_tecnica' => $naturaleza_tecnica,
                'objetivo' => $objetivo
            ]);
            return back()->with('success', 'Proyecto actualizado exitosamente.');
        }
    }

    /**
     * Elimina un proyecto (usado en el CRUD de administración).
     */
    public function eliminar(Request $request)
    {
        $clave_proyecto = $request->input('clave_proyecto_eliminar');
        DB::table('proyecto')->where('clave_proyecto', '=', $clave_proyecto)->delete();
        return back()->with('success', 'Proyecto eliminado exitosamente.');
    }

    /**
     * Elimina múltiples proyectos (usado en el CRUD de administración).
     */
    public function eliminarMultiple(Request $request)
    {
        $proyectos_a_eliminar = $request->input('proyectos_seleccionados', []); // Obtén un array de claves

        if (empty($proyectos_a_eliminar)) {
            return back()->with('error', 'No se seleccionaron proyectos para eliminar.');
        }

        DB::table('proyecto')->whereIn('clave_proyecto', $proyectos_a_eliminar)->delete();
        return back()->with('success', 'Proyectos seleccionados eliminados exitosamente.');
    }


    // ========================================================================
    // MÉTODOS PARA LA EDICIÓN DE PROYECTOS POR EL LÍDER DE EQUIPO
    // ========================================================================

    /**
     * Muestra el formulario para editar un proyecto específico.
     * Solo accesible por el admin o el líder del proyecto.
     *
     * @param string $clave_proyecto La clave del proyecto a editar.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($clave_proyecto)
    {
        $user = Auth::user();
        $proyecto = DB::table('proyecto')->where('clave_proyecto', $clave_proyecto)->first();

        if (!$proyecto) {
            return redirect()->route('home')->with('error', 'Proyecto no encontrado.');
        }

        $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();
        $esLider = false;
        if ($alumno) {
            $liderCheck = DB::table('alumno_proyecto')
                          ->where('clave_proyecto', $clave_proyecto)
                          ->where('no_control', $alumno->no_control)
                          ->where('lider', 1)
                          ->first();
            if ($liderCheck) {
                $esLider = true;
            }
        }

        if (!$user->hasRole('admin') && !$esLider) {
            return redirect()->route('home')->with('error', 'No tienes permiso para editar este proyecto.');
        }

        $categorias = DB::table('categoria')->get();
        $tipos = DB::table('tipo')->get();
        $etapas = DB::table('etapas')->get();

        return view('alumnos.editar', compact('proyecto', 'categorias', 'tipos', 'etapas'));
    }

    /**
     * Actualiza los datos de un proyecto específico.
     * Solo accesible por el admin o el líder del proyecto.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP.
     * @param string $clave_proyecto La clave del proyecto a actualizar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $clave_proyecto)
    {
        $user = Auth::user();

        $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();
        $esLider = false;
        if ($alumno) {
            $liderCheck = DB::table('alumno_proyecto')
                          ->where('clave_proyecto', $clave_proyecto)
                          ->where('no_control', $alumno->no_control)
                          ->where('lider', 1)
                          ->first();
            if ($liderCheck) {
                $esLider = true;
            }
        }

        if (!$user->hasRole('admin') && !$esLider) {
            return redirect()->route('home')->with('error', 'No tienes permiso para editar este proyecto.');
        }

        // --- CAMBIO CLAVE AQUÍ: Transformar '' a null para el campo 'video' antes de la validación ---
        if (empty($request->input('video'))) {
            $request->merge(['video' => null]);
        }
        // -----------------------------------------------------------------------------------------

        // Validar los datos de la solicitud
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|integer|exists:categoria,idCategoria',
            'tipo' => 'required|integer|exists:tipo,idTipo',
            'etapa' => 'required|integer|exists:etapas,idEtapa',
            'video' => 'nullable|url:http,https', // Ahora funcionará bien si el campo es null
            'area_aplicacion' => 'nullable|string|max:255',
            'naturaleza_tecnica' => 'nullable|string|max:255',
            'objetivo' => 'nullable|string',
        ]);

        try {
            DB::table('proyecto')
                ->where('clave_proyecto', $clave_proyecto)
                ->update([
                    'nombre' => $request->input('nombre'),
                    'descripcion' => $request->input('descripcion'),
                    'categoria' => $request->input('categoria'),
                    'tipo' => $request->input('tipo'),
                    'etapa' => $request->input('etapa'),
                    'video' => $request->input('video'), // Ahora $request->input('video') será null si estaba vacío
                    'area_aplicacion' => $request->input('area_aplicacion'),
                    'naturaleza_tecnica' => $request->input('naturaleza_tecnica'),
                    'objetivo' => $request->input('objetivo'),
                ]);

            return redirect()->route('home')->with('success', 'Proyecto actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el proyecto: ' . $e->getMessage());
        }
    }
}
