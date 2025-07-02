<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AdminActivityNotification; // Importa tu clase de notificación
use App\Models\User; // Importa el modelo User

class ProyectoController extends Controller
{
    // Define los IDs de etapa para los estados de la propuesta según tu tabla 'etapas'
    const ID_ETAPA_PENDIENTE = 1; // Corresponde a 'PENDIENTE'
    const ID_ETAPA_APROBADA = 3;    // Corresponde a 'V.º B.º Administrador'
    const ID_ETAPA_RECHAZADA = 4;  // ¡¡¡CAMBIA ESTE VALOR SI USAS OTRO ID PARA 'RECHAZADA'!!!
                                   // Si no tienes una etapa 'RECHAZADA', usa 1 y el motivo_rechazo para diferenciar

    public function __construct()
    {
        $this->middleware('auth'); // Asegura que el usuario esté autenticado para todos los métodos
    }

    /**
     * Muestra la vista principal del CRUD de proyectos (probablemente para administradores).
     * Filtra los proyectos que son propuestas pendientes o rechazadas.
     */
    public function index()
    {
        $proyectos = DB::table('proyecto')
                        ->where('etapa', '!=', self::ID_ETAPA_PENDIENTE)
                        ->where('etapa', '!=', self::ID_ETAPA_RECHAZADA)
                        ->orderBy('fecha_agregado', 'desc')
                        ->get();
        $columnas = ['Clave', 'Nombre', 'Nombre descriptivo', 'Categoria', 'Tipo', 'Etapa', 'Video', 'Registrado'];
        $categorias = DB::table('categoria')->get();
        $alumnos = DB::table('alumno')->get();
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
     * Los proyectos agregados por el admin se consideran aprobados por defecto.
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
                'etapa' => $etapa, // Se usa la etapa proporcionada por el admin
                'video' => $video,
                'area_aplicacion' => $area_aplicacion,
                'naturaleza_tecnica' => $naturaleza_tecnica,
                'objetivo' => $objetivo,
                'fecha_agregado' => now(),
            ]);

            // Notificación para administradores: Proyecto Agregado (directamente aprobado)
            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new AdminActivityNotification('Proyecto "' . $nombre . '" agregado directamente por administrador.', '/c_proyectos', 'proyecto_agregado_admin'));
            }

            return back()->with('success', 'Proyecto agregado exitosamente.');
        }
    }

    /**
     * Actualiza un proyecto desde la interfaz de administración.
     */
    public function editar(Request $request)
    {
        $clave_proyecto = $request->input('clave_proyecto_editar');
        $clave_proyecto_mod = $request->input('clave_proyecto_mod');
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

            // Notificación para administradores: Proyecto Actualizado (Admin)
            $admins = User::role('admin')->get();
            $notificationMessage = "Proyecto '" . $nombre . "' actualizado por un administrador.";
            foreach ($admins as $admin) {
                $admin->notify(new AdminActivityNotification($notificationMessage, '/c_proyectos', 'proyecto_actualizado_admin'));
            }

            return back()->with('success', 'Proyecto actualizado exitosamente.');
        }
    }

    /**
     * Elimina un proyecto (usado en el CRUD de administración).
     */
    public function eliminar(Request $request)
    {
        $clave_proyecto = $request->input('clave_proyecto_eliminar');
        $proyecto_eliminado = DB::table('proyecto')->where('clave_proyecto', '=', $clave_proyecto)->first();

        DB::table('proyecto')->where('clave_proyecto', '=', $clave_proyecto)->delete();

        // Notificación para administradores: Proyecto Eliminado
        if ($proyecto_eliminado) {
            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new AdminActivityNotification('Proyecto "' . $proyecto_eliminado->nombre . '" (Clave: ' . $clave_proyecto . ') eliminado.', '/c_proyectos', 'proyecto_eliminado_admin'));
            }
        }

        return back()->with('success', 'Proyecto eliminado exitosamente.');
    }

    /**
     * Elimina múltiples proyectos (usado en el CRUD de administración).
     */
    public function eliminarMultiple(Request $request)
    {
        $proyectos_a_eliminar = $request->input('proyectos_seleccionados', []);

        if (empty($proyectos_a_eliminar)) {
            return back()->with('error', 'No se seleccionaron proyectos para eliminar.');
        }

        $nombres_proyectos = DB::table('proyecto')->whereIn('clave_proyecto', $proyectos_a_eliminar)->pluck('nombre')->implode(', ');

        DB::table('proyecto')->whereIn('clave_proyecto', $proyectos_a_eliminar)->delete();

        // Notificación para administradores: Múltiples Proyectos Eliminados
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

        if (empty($request->input('video'))) {
            $request->merge(['video' => null]);
        }

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
                    'video' => $request->input('video'),
                    'area_aplicacion' => $request->input('area_aplicacion'),
                    'naturaleza_tecnica' => $request->input('naturaleza_tecnica'),
                    'objetivo' => $request->input('objetivo'),
                ]);

            // Notificación para administradores: Proyecto Actualizado (Líder/Admin)
            $admins = User::role('admin')->get();
            $updaterName = $user->name;
            $adminMessage = 'Proyecto "' . $request->input('nombre') . '" (Clave: ' . $clave_proyecto . ') actualizado por ' . $updaterName . '.';
            foreach ($admins as $admin) {
                $admin->notify(new AdminActivityNotification($adminMessage, '/proyectos/' . $clave_proyecto . '/editar', 'proyecto_actualizado_lider'));
            }

            return redirect()->route('home')->with('success', 'Proyecto actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el proyecto: ' . $e->getMessage());
        }
    }


    // ========================================================================
    // MÉTODOS PARA CREAR Y REVISAR PROPUESTAS DE PROYECTO
    // ========================================================================

    /**
     * Muestra el formulario para que cualquier alumno cree una propuesta de proyecto.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function createProposalForm()
    {
        $user = Auth::user();
        $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();

        // Cualquier usuario con rol 'alumno' puede crear una propuesta
        if (!$user->hasRole('alumno') || !$alumno) {
            return redirect()->route('home')->with('error', 'No tienes permiso para crear una propuesta de proyecto.');
        }

        $categorias = DB::table('categoria')->get();
        $tipos = DB::table('tipo')->get();
        // Las etapas se pasan para que el formulario sea consistente, aunque se asigna PENDIENTE por defecto
        $etapas = DB::table('etapas')->get();

        return view('alumnos.crear_propuesta', compact('categorias', 'tipos', 'etapas'));
    }

    /**
     * Almacena la nueva propuesta de proyecto enviada por un alumno.
     * El alumno que la crea se asocia automáticamente como líder.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeProposal(Request $request)
    {
        $user = Auth::user();
        $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();

        if (!$user->hasRole('alumno') || !$alumno) {
            return redirect()->route('home')->with('error', 'Acceso no autorizado para crear propuestas.');
        }

        // Validar los datos de la solicitud
        $request->validate([
            'clave_proyecto' => 'required|string|max:50|unique:proyecto,clave_proyecto',
            'nombre' => 'required|string|max:255',
            'nombre_descriptivo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|integer|exists:categoria,idCategoria',
            'tipo' => 'required|integer|exists:tipo,idTipo',
            'video' => 'nullable|url:http,https',
            'area_aplicacion' => 'nullable|string|max:255',
            'naturaleza_tecnica' => 'nullable|string|max:255',
            'objetivo' => 'nullable|string',
        ]);

        // Transformar '' a null para el campo 'video' si está vacío
        if (empty($request->input('video'))) {
            $request->merge(['video' => null]);
        }

        try {
            // Insertar el proyecto con la etapa PENDIENTE (ID 1)
            DB::table('proyecto')->insert([
                'clave_proyecto' => $request->input('clave_proyecto'),
                'nombre' => $request->input('nombre'),
                'nombre_descriptivo' => $request->input('nombre_descriptivo'),
                'descripcion' => $request->input('descripcion'),
                'categoria' => $request->input('categoria'),
                'tipo' => $request->input('tipo'),
                'etapa' => self::ID_ETAPA_PENDIENTE, // ¡Aquí se asigna la etapa 1 (PENDIENTE)!
                'video' => $request->input('video'),
                'area_aplicacion' => $request->input('area_aplicacion'),
                'naturaleza_tecnica' => $request->input('naturaleza_tecnica'),
                'objetivo' => $request->input('objetivo'),
                'fecha_agregado' => now(), // Registra la fecha de creación
            ]);

            // Asociar al alumno que creó la propuesta como líder del proyecto
            // Esta relación se establecerá como líder al momento de la creación de la propuesta.
            DB::table('alumno_proyecto')->insert([
                'no_control' => $alumno->no_control,
                'clave_proyecto' => $request->input('clave_proyecto'),
                'lider' => 1, // Marcar como líder desde el inicio de la propuesta
            ]);

            // Notificación para administradores: Nueva Propuesta de Proyecto
            $admins = User::role('admin')->get();
            $proposalLink = route('admin.proyectos.propuestas'); // Enlace a la lista de propuestas
            foreach ($admins as $admin) {
                $admin->notify(new AdminActivityNotification(
                    'Nueva propuesta de proyecto de ' . $user->name . ': "' . $request->input('nombre') . '" (Clave: ' . $request->input('clave_proyecto') . ')',
                    $proposalLink,
                    'proposal_submitted'
                ));
            }

            return redirect()->route('home')->with('success', 'Propuesta de proyecto creada exitosamente y pendiente de revisión.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al crear la propuesta: ' . $e->getMessage());
        }
    }

    /**
     * Muestra la lista de propuestas de proyectos para el administrador.
     *
     * @return \Illuminate\View\View
     */
    public function listProposals()
    {
        // Obtener solo proyectos que estén en estado 'PENDIENTE' o 'RECHAZADA'
        $propuestas = DB::table('proyecto')
                        ->leftJoin('categoria', 'proyecto.categoria', '=', 'categoria.idCategoria')
                        ->leftJoin('tipo', 'proyecto.tipo', '=', 'tipo.idTipo')
                        ->leftJoin('etapas', 'proyecto.etapa', '=', 'etapas.idEtapa')
                        ->select(
                            'proyecto.clave_proyecto',
                            'proyecto.nombre',
                            'proyecto.descripcion',
                            'categoria.nombre as nombre_categoria',
                            'tipo.nombre as nombre_tipo',
                            'etapas.nombre as nombre_etapa', // El nombre de la etapa actual
                            'proyecto.fecha_agregado',
                            'proyecto.etapa', // El ID de la etapa
                            'proyecto.motivo_rechazo'
                        )
                        ->whereIn('proyecto.etapa', [self::ID_ETAPA_PENDIENTE, self::ID_ETAPA_RECHAZADA])
                        ->orderBy('fecha_agregado', 'desc')
                        ->get();

        $titulo = "Revisión de Propuestas de Proyectos";

        return view('Admin.propuestas_proyectos', compact('propuestas', 'titulo'));
    }

    /**
     * Procesa la revisión de una propuesta de proyecto (aceptar/rechazar).
     *
     * @param \Illuminate\Http\Request $request
     * @param string $clave_proyecto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reviewProposal(Request $request, $clave_proyecto)
    {
        $request->validate([
            'action' => 'required|in:accept,reject',
            'motivo_rechazo' => 'nullable|string|max:500',
        ]);

        $proyecto = DB::table('proyecto')->where('clave_proyecto', $clave_proyecto)->first();

        if (!$proyecto) {
            return back()->with('error', 'Propuesta de proyecto no encontrada.');
        }

        $updateData = [];
        $notificationMessage = '';
        $notificationType = '';
        $motivoRechazo = null;

        if ($request->input('action') === 'accept') {
            $updateData = [
                'etapa' => self::ID_ETAPA_APROBADA, // Cambia la etapa a APROBADA (3)
                'motivo_rechazo' => null, // Limpiar motivo de rechazo si se acepta
            ];
            $notificationMessage = 'La propuesta de proyecto "' . $proyecto->nombre . '" ha sido APROBADA.';
            $notificationType = 'proposal_approved';
            // No se necesita actualizar 'lider' aquí, ya que se asignó al crear la propuesta
        } elseif ($request->input('action') === 'reject') {
            if (empty($request->input('motivo_rechazo'))) {
                return back()->with('error', 'El motivo de rechazo es obligatorio para rechazar la propuesta.');
            }
            $motivoRechazo = $request->input('motivo_rechazo');
            $updateData = [
                'etapa' => self::ID_ETAPA_RECHAZADA, // Cambia la etapa a RECHAZADA (4)
                'motivo_rechazo' => $motivoRechazo,
            ];
            $notificationMessage = 'La propuesta de proyecto "' . $proyecto->nombre . '" ha sido RECHAZADA. Motivo: ' . $motivoRechazo;
            $notificationType = 'proposal_rejected';
        }

        DB::table('proyecto')->where('clave_proyecto', $clave_proyecto)->update($updateData);

        // Notificar al alumno líder sobre la decisión
        $leader = DB::table('alumno_proyecto')
                    ->where('clave_proyecto', $clave_proyecto)
                    ->where('lider', 1) // Obtener al alumno que ya es líder de esta propuesta
                    ->join('alumno', 'alumno_proyecto.no_control', '=', 'alumno.no_control')
                    ->join('users', 'alumno.correo_institucional', '=', 'users.email')
                    ->select('users.id')
                    ->first();

        if ($leader) {
            $leaderUser = User::find($leader->id);
            if ($leaderUser) {
                $leaderUser->notify(new AdminActivityNotification(
                    $notificationMessage,
                    route('home'), // O una ruta específica para ver sus proyectos
                    $notificationType
                ));
            }
        }

        return back()->with('success', 'Propuesta de proyecto procesada exitosamente.');
    }
}
