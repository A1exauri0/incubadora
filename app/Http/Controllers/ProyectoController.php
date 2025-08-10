<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AdminActivityNotification;
// use App\Notifications\NewProposalNotification; // Eliminada, ahora en PropuestaProyectoController
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class ProyectoController extends Controller
{
    // Constantes de etapa, SOLO las necesarias para este controlador (CRUD general)
    // Las constantes de APROBADA y RECHAZADA podrían no ser estrictamente necesarias aquí
    // si solo se usan en PropuestaProyectoController.
    // Mantenemos solo PENDIENTE si es que se usa para algo más que propuestas.
    // Si no se usan en ningún método de este controlador, se pueden eliminar.
    // Las dejamos por si acaso se usan para mostrar estados en el index.
    const ID_ETAPA_PENDIENTE = 1;
    const ID_ETAPA_APROBADA = 3;
    const ID_ETAPA_RECHAZADA = 4;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra la lista general de proyectos en el CRUD de administración.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtiene todos los proyectos ordenados por fecha de agregado
        $proyectos = DB::table('proyecto')
            ->orderBy('fecha_agregado', 'desc')
            ->get();

        // Define las columnas para la tabla en la vista
        $columnas = ['Clave', 'Nombre', 'Nombre descriptivo', 'Categoria', 'Tipo', 'Etapa', 'Video', 'Registrado'];
        // Obtiene datos de tablas relacionadas para los selectores o mostrar nombres en la vista
        $categorias = DB::table('categoria')->get();
        $alumnos = DB::table('alumno')->get(); // Puede que no sea necesario si solo es para listar proyectos
        $tipos = DB::table('tipo')->get();
        $etapas = DB::table('etapas')->get();
        $total_registros = $proyectos->count();

        $titulo = "CRUD Proyectos";

        // Retorna la vista con los datos del proyecto
        return view('Admin.cruds.proyectos', compact('proyectos', 'titulo', 'columnas', 'total_registros', 'categorias', 'alumnos', 'tipos', 'etapas'));
    }

    /**
     * Verifica si una clave de proyecto ya existe en la base de datos.
     *
     * @param  string  $clave_proyecto
     * @return bool
     */
    public function registro_existe($clave_proyecto)
    {
        $existe = DB::table('proyecto')->where('clave_proyecto', '=', $clave_proyecto)->count();
        return $existe > 0;
    }

    /**
     * Agrega un nuevo proyecto directamente desde la interfaz de administración.
     * Notifica a los administradores sobre el proyecto agregado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function agregar(Request $request)
    {
        // Obtiene los datos del formulario
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

        // Verifica si la clave del proyecto ya existe
        if ($this->registro_existe($clave_proyecto)) {
            return back()->with('error', 'La clave del proyecto que intentó agregar ya existe en otro registro.');
        } else {
            // Inserta el nuevo proyecto en la base de datos
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
                'objetivo' => $objetivo,
                'fecha_agregado' => now(), // Fecha de creación
            ]);

            // Notifica a todos los administradores sobre el proyecto agregado
            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new AdminActivityNotification('Proyecto "' . $nombre . '" agregado directamente por administrador.', '/c_proyectos', 'proyecto_agregado_admin'));
            }

            return back()->with('success', 'Proyecto agregado exitosamente.');
        }
    }

    /**
     * Edita un proyecto existente desde la interfaz de administración.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editar(Request $request)
    {
        // Obtiene los datos del formulario de edición
        $clave_proyecto = $request->input('clave_proyecto_editar'); // Clave original del proyecto
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

        // Verifica si la nueva clave de proyecto ya existe y es diferente de la original
        if ($clave_proyecto != $clave_proyecto_mod && $this->registro_existe($clave_proyecto_mod)) {
            return back()->with('error', 'La clave del proyecto que intentó modificar ya existe en otro registro.');
        } else {
            // Actualiza el proyecto en la base de datos
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

            // Notifica a los administradores sobre la actualización del proyecto
            $admins = User::role('admin')->get();
            $updaterName = Auth::user()->name; // Nombre del usuario que realizó la actualización
            $adminMessage = 'Proyecto "' . $nombre . '" actualizado por un administrador.';
            foreach ($admins as $admin) {
                $admin->notify(new AdminActivityNotification($adminMessage, '/c_proyectos', 'proyecto_actualizado_admin'));
            }

            return back()->with('success', 'Proyecto actualizado exitosamente.');
        }
    }

    /**
     * Elimina un proyecto específico de la base de datos.
     * También elimina los requerimientos y resultados asociados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function eliminar(Request $request)
    {
        $clave_proyecto = $request->input('clave_proyecto_eliminar');
        $proyecto_eliminado = DB::table('proyecto')->where('clave_proyecto', '=', $clave_proyecto)->first();

        // Eliminar requerimientos y resultados asociados primero
        DB::table('proyecto_requerimientos')->where('clave_proyecto', $clave_proyecto)->delete();
        DB::table('proyecto_resultados')->where('clave_proyecto', $clave_proyecto)->delete();
        // Luego eliminar el proyecto
        DB::table('proyecto')->where('clave_proyecto', '=', $clave_proyecto)->delete();

        // Notifica a los administradores sobre el proyecto eliminado
        if ($proyecto_eliminado) {
            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new AdminActivityNotification('Proyecto "' . $proyecto_eliminado->nombre . '" (Clave: ' . $clave_proyecto . ') eliminado.', '/c_proyectos', 'proyecto_eliminado_admin'));
            }
        }

        return back()->with('success', 'Proyecto eliminado exitosamente.');
    }

    /**
     * Elimina múltiples proyectos seleccionados de la base de datos.
     * También elimina los requerimientos y resultados asociados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function eliminarMultiple(Request $request)
    {
        $proyectos_a_eliminar = [];

        // Recorre todos los inputs de la solicitud para encontrar las claves de proyecto a eliminar
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'clave_proyecto_eliminar_') === 0 && !empty($value)) {
                $proyectos_a_eliminar[] = $value;
            }
        }

        // Si no se seleccionaron proyectos, redirige con un error
        if (empty($proyectos_a_eliminar)) {
            return back()->with('error', 'No se seleccionaron proyectos para eliminar.');
        }

        // Obtiene los nombres de los proyectos a eliminar para el mensaje de notificación
        $nombres_proyectos = DB::table('proyecto')->whereIn('clave_proyecto', $proyectos_a_eliminar)->pluck('nombre')->implode(', ');

        // Eliminar requerimientos y resultados para múltiples proyectos
        DB::table('proyecto_requerimientos')->whereIn('clave_proyecto', $proyectos_a_eliminar)->delete();
        DB::table('proyecto_resultados')->whereIn('clave_proyecto', $proyectos_a_eliminar)->delete();
        // Luego eliminar los proyectos
        DB::table('proyecto')->whereIn('clave_proyecto', $proyectos_a_eliminar)->delete();

        // Notifica a los administradores sobre la eliminación múltiple de proyectos
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $message = 'Múltiples proyectos eliminados. Claves: ' . implode(', ', $proyectos_a_eliminar);
            if (!empty($nombres_proyectos)) {
                $message = 'Múltiples proyectos eliminados: ' . $nombres_proyectos;
            }
            $admin->notify(new AdminActivityNotification($message, '/c_proyectos', 'proyectos_eliminados_admin'));
        }

        return back()->with('success', 'Proyectos seleccionados eliminados exitosamente.');
    }

    /**
     * Muestra el formulario para editar un proyecto existente.
     * Verifica permisos de administrador o líder de proyecto.
     *
     * @param  string  $clave_proyecto La clave del proyecto a editar.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($clave_proyecto)
    {
        /** @var \App\Models\User */
        $user = Auth::user(); // Obtiene el usuario autenticado
        $proyecto = DB::table('proyecto')->where('clave_proyecto', $clave_proyecto)->first();

        // Si el proyecto no se encuentra, redirige con un error
        if (!$proyecto) {
            return redirect()->route('home')->with('error', 'Proyecto no encontrado.');
        }

        $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();
        $esLider = false;
        // Verifica si el usuario actual es el líder del proyecto
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

        // Si el usuario no es admin y no es el líder del proyecto, niega el permiso
        if (!$user->hasRole('admin') && !$esLider) {
            return redirect()->route('home')->with('error', 'No tienes permiso para editar este proyecto.');
        }

        // Obtiene datos de tablas relacionadas para los selectores del formulario de edición
        $categorias = DB::table('categoria')->get();
        $tipos = DB::table('tipo')->get();
        $etapas = DB::table('etapas')->get();

        // Obtiene requerimientos y resultados existentes del proyecto para mostrarlos en el formulario
        $requerimientos = DB::table('proyecto_requerimientos')->where('clave_proyecto', $clave_proyecto)->get();
        $resultados = DB::table('proyecto_resultados')->where('clave_proyecto', $clave_proyecto)->get();

        // Retorna la vista de edición con los datos del proyecto
        return view('alumnos.editar', compact('proyecto', 'categorias', 'tipos', 'etapas', 'requerimientos', 'resultados'));
    }

    /**
     * Actualiza un proyecto existente.
     * Verifica permisos y sincroniza requerimientos y resultados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $clave_proyecto La clave del proyecto a actualizar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $clave_proyecto)
    {
        /** @var \App\Models\User */
        $user = Auth::user(); // Obtiene el usuario autenticado

        $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();
        $esLider = false;
        // Verifica si el usuario actual es el líder del proyecto
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

        // Si el usuario no es admin y no es el líder del proyecto, niega el permiso
        if (!$user->hasRole('admin') && !$esLider) {
            return redirect()->route('home')->with('error', 'No tienes permiso para editar este proyecto.');
        }

        // Si el campo de video está vacío, lo establece como null para evitar errores de validación de URL
        if (empty($request->input('video'))) {
            $request->merge(['video' => null]);
        }

        // Define las reglas de validación para los datos del proyecto
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|integer|exists:categoria,idCategoria',
            'tipo' => 'required|integer|exists:tipo,idTipo',
            'etapa' => 'required|integer|exists:etapas,idEtapa',
            'video' => 'nullable|url:http,https',
            'area_aplicacion' => 'nullable|string|max:255',
            'naturaleza_tecnica' => 'nullable|string|max:255',
            'objetivo' => 'nullable|string',
            // Validaciones para requerimientos
            'requerimientos' => 'nullable|array',
            'requerimientos.*.descripcion' => 'required_with:requerimientos.*.cantidad|string|max:100',
            'requerimientos.*.cantidad' => 'required_with:requerimientos.*.descripcion|string|max:50',
            // Validaciones para resultados
            'resultados' => 'nullable|array',
            'resultados.*.descripcion' => 'required|string|max:100',
        ]);

        try {
            // Actualiza los datos principales del proyecto
            DB::table('proyecto')
                ->where('clave_proyecto', $clave_proyecto)
                ->update([
                    'nombre' => $request->input('nombre'),
                    'descripcion' => $request->input('descripcion'),
                    'categoria' => $request->input('categoria'),
                    'tipo' => $request->input('tipo'),
                    'etapa' => $request->input('etapa'),
                    'video' => $request->input('video'),
                    'area_aplicacion' => $request->input('area_aplicacion'),
                    'naturaleza_tecnica' => $request->input('naturaleza_tecnica'),
                    'objetivo' => $request->input('objetivo'),
                ]);

            // Sincroniza requerimientos: elimina los existentes y luego inserta los nuevos
            DB::table('proyecto_requerimientos')->where('clave_proyecto', $clave_proyecto)->delete();
            if ($request->has('requerimientos') && is_array($request->input('requerimientos'))) {
                foreach ($request->input('requerimientos') as $req) {
                    if (!empty($req['descripcion']) && !empty($req['cantidad'])) {
                        DB::table('proyecto_requerimientos')->insert([
                            'clave_proyecto' => $clave_proyecto,
                            'descripcion' => $req['descripcion'],
                            'cantidad' => $req['cantidad'],
                        ]);
                    }
                }
            }

            // Sincroniza resultados: elimina los existentes y luego inserta los nuevos
            DB::table('proyecto_resultados')->where('clave_proyecto', $clave_proyecto)->delete();
            if ($request->has('resultados') && is_array($request->input('resultados'))) {
                foreach ($request->input('resultados') as $res) {
                    if (!empty($res['descripcion'])) {
                        DB::table('proyecto_resultados')->insert([
                            'clave_proyecto' => $clave_proyecto,
                            'descripcion' => $res['descripcion'],
                            'fecha_agregado' => now(), // Añadir la fecha de agregado
                        ]);
                    }
                }
            }

            // Notifica a los administradores sobre la actualización del proyecto por un líder
            $admins = User::role('admin')->get();
            $updaterName = Auth::user()->name;
            $adminMessage = 'Proyecto "' . $request->input('nombre') . '" (Clave: ' . $clave_proyecto . ') actualizado por ' . $updaterName . '.';
            foreach ($admins as $admin) {
                $admin->notify(new AdminActivityNotification($adminMessage, '/proyectos/' . $clave_proyecto . '/editar', 'proyecto_actualizado_lider'));
            }

            return redirect()->route('home')->with('success', 'Proyecto actualizado exitosamente.');

        } catch (\Exception $e) {
            // Maneja cualquier error durante la actualización
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Genera la ficha técnica de un proyecto en formato PDF.
     *
     * @param string $clave_proyecto La clave del proyecto.
     * @return \Illuminate\Http\Response
     */
    public function generateFichaTecnicaPdf($clave_proyecto)
    {
        // Obtener los datos del proyecto, incluyendo el nombre de la categoría
        $proyecto = DB::table('proyecto')
            ->where('clave_proyecto', $clave_proyecto)
            ->leftJoin('categoria', 'proyecto.categoria', '=', 'categoria.idCategoria')
            ->select('proyecto.*', 'categoria.nombre as nombre_categoria')
            ->first();

        if (!$proyecto) {
            abort(404, 'Proyecto no encontrado.');
        }

        // Obtener los resultados del proyecto
        $resultados = DB::table('proyecto_resultados')
            ->where('clave_proyecto', $clave_proyecto)
            ->get();

        // Obtener los alumnos asociados al proyecto con su carrera
        $alumno_proyecto = DB::table('alumno_proyecto')
            ->where('clave_proyecto', $clave_proyecto)
            ->join('alumno', 'alumno_proyecto.no_control', '=', 'alumno.no_control')
            ->leftJoin('carrera', 'alumno.carrera', '=', 'carrera.nombre') // Unir por el nombre de la carrera
            ->select('alumno.*', 'carrera.nombre as carrera_nombre_completo', 'alumno_proyecto.lider')
            ->get();

        // Obtener los asesores asociados al proyecto
        $asesor_proyecto = DB::table('asesor_proyecto')
            ->where('clave_proyecto', $clave_proyecto)
            ->join('asesor', 'asesor_proyecto.idAsesor', '=', 'asesor.idAsesor')
            ->select('asesor.*')
            ->get();

        // Obtener los requerimientos del proyecto
        $requerimientos = DB::table('proyecto_requerimientos')
            ->where('clave_proyecto', $clave_proyecto)
            ->get();

        // Preparar los datos para la vista del PDF
        $data = [
            'proyecto' => $proyecto,
            'resultados' => $resultados,
            'alumno_proyecto' => $alumno_proyecto,
            'asesor_proyecto' => $asesor_proyecto,
            'requerimientos' => $requerimientos,
            'categorias' => DB::table('categoria')->get(),
            'carreras' => DB::table('carrera')->get(),
        ];

        // Cargar la vista para generar el PDF
        $pdf = Pdf::loadView('layouts.pdf', $data);

        // Visualizar el PDF en el navegador
        return $pdf->stream('ficha_tecnica_' . $clave_proyecto . '.pdf');
    }
}
