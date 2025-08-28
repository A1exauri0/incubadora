<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AdminActivityNotification;
use App\Notifications\NewProposalNotification;
use App\Notifications\AsesorActivityNotification; 
use App\Notifications\StudentProposalStatusNotification;
use App\Models\User;

class PropuestaProyectoController extends Controller
{
    // Constantes para las etapas de los proyectos
    const ID_ETAPA_PENDIENTE_ASESOR = 1; // Pendiente de revisión del asesor
    const ID_ETAPA_VISTO_BUENO_ASESOR = 2; // Aprobada por el asesor, pendiente del administrador
    const ID_ETAPA_APROBADA_ADMIN = 3;   // Aprobada por el administrador (Aprobada)
    const ID_ETAPA_RECHAZADA = 4;        // Rechazada

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra el formulario para que un alumno cree una nueva propuesta de proyecto.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function createProposalForm()
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();

        if (!$user->hasRole('alumno') || !$alumno) {
            return redirect()->route('home')->with('error', 'No tienes permiso para crear una propuesta de proyecto.');
        }

        $categorias = DB::table('categoria')->get();
        $tipos = DB::table('tipo')->get();
        // Las etapas no se usan directamente en el formulario de creación, pero se mantienen si la vista las espera.
        $etapas = DB::table('etapas')->get(); 
        
        // Obtenemos los asesores desde la tabla 'users' para el formulario
        $asesores = User::role('asesor')->get();

        return view('alumnos.crear_propuesta', compact('categorias', 'tipos', 'etapas', 'asesores'));
    }

    /**
     * Almacena una nueva propuesta de proyecto enviada por un alumno.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeProposal(Request $request)
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();

        if (!$user->hasRole('alumno') || !$alumno) {
            return redirect()->route('home')->with('error', 'Acceso no autorizado para crear propuestas.');
        }

        // Si el campo de video está vacío, lo normalizamos a null
        if (empty($request->input('video'))) {
            $request->merge(['video' => null]);
        }

        // Las reglas de validación ya NO incluyen 'clave_proyecto'
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nombre_descriptivo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|integer|exists:categoria,idCategoria',
            'tipo' => 'required|integer|exists:tipo,idTipo',
            'asesor_id' => 'required|integer|exists:users,id',
            'video' => 'nullable|url:http,https',
            'area_aplicacion' => 'nullable|string|max:255',
            'naturaleza_tecnica' => 'nullable|string|max:255',
            'objetivo' => 'nullable|string',
            'requerimientos' => 'required|array',
            'requerimientos.*.descripcion' => 'required_with:requerimientos.*.cantidad|string|max:100',
            'requerimientos.*.cantidad' => 'required_with:requerimientos.*.descripcion|string|max:50',
            'resultados' => 'nullable|array',
            'resultados.*.descripcion' => 'required|string|max:100',
        ]);

        try {
            // --- Lógica para GENERAR la clave del proyecto autoincremental ---
            $lastProyecto = DB::table('proyecto')
                                ->orderBy('clave_proyecto', 'desc')
                                ->first();

            $nextNumericId = 1;
            if ($lastProyecto) {
                // Extraemos la parte numérica del último clave_proyecto
                // Asumimos que la clave siempre es un número con ceros a la izquierda
                $lastNumericPart = (int) $lastProyecto->clave_proyecto;
                $nextNumericId = $lastNumericPart + 1;
            } else {
                // Si no hay proyectos, empezamos desde 49 + 1 = 50 para seguir la secuencia '0000000000049'
                $nextNumericId = 50; 
            }
            
            // Formateamos la nueva clave del proyecto con ceros a la izquierda para 11 dígitos
            $generatedClaveProyecto = str_pad($nextNumericId, 11, '0', STR_PAD_LEFT);
            // --- FIN de la lógica de generación ---


            DB::table('proyecto')->insert([
                'clave_proyecto' => $generatedClaveProyecto, // Usamos la clave generada
                'nombre' => $request->input('nombre'),
                'nombre_descriptivo' => $request->input('nombre_descriptivo'),
                'descripcion' => $request->input('descripcion'),
                'categoria' => $request->input('categoria'),
                'tipo' => $request->input('tipo'),
                'etapa' => self::ID_ETAPA_PENDIENTE_ASESOR,
                'video' => $request->input('video'),
                'area_aplicacion' => $request->input('area_aplicacion'),
                'naturaleza_tecnica' => $request->input('naturaleza_tecnica'),
                'objetivo' => $request->input('objetivo'),
                'fecha_agregado' => now(),
            ]);

            $clave_proyecto_insertado = $generatedClaveProyecto; // Usamos la clave generada

            // Insertar requerimientos
            if ($request->has('requerimientos') && is_array($request->input('requerimientos'))) {
                foreach ($request->input('requerimientos') as $req) {
                    if (!empty($req['descripcion']) && !empty($req['cantidad'])) {
                        DB::table('proyecto_requerimientos')->insert([
                            'clave_proyecto' => $clave_proyecto_insertado,
                            'descripcion' => $req['descripcion'],
                            'cantidad' => $req['cantidad'],
                        ]);
                    }
                }
            }

            // Insertar resultados esperados
            if ($request->has('resultados') && is_array($request->input('resultados'))) {
                foreach ($request->input('resultados') as $res) {
                    if (!empty($res['descripcion'])) {
                        DB::table('proyecto_resultados')->insert([
                            'clave_proyecto' => $clave_proyecto_insertado,
                            'descripcion' => $res['descripcion'],
                            'fecha_agregado' => now(),
                        ]);
                    }
                }
            }

            // Asignar alumno como líder del proyecto
            DB::table('alumno_proyecto')->insert([
                'no_control' => $alumno->no_control,
                'clave_proyecto' => $clave_proyecto_insertado,
                'lider' => 1,
            ]);

            // Obtener el usuario asesor del formulario
            $asesorUser = User::find($request->input('asesor_id'));
            
            // Buscar el ID del asesor en la tabla 'asesor' usando el correo del usuario
            if ($asesorUser && $asesorUser->hasRole('asesor')) {
                $asesorData = DB::table('asesor')->where('correo_electronico', $asesorUser->email)->first();
                
                if ($asesorData) {
                    // Guardar la relación en la tabla `asesor_proyecto` con el ID correcto
                    DB::table('asesor_proyecto')->insert([
                        'idAsesor' => $asesorData->idAsesor, 
                        'clave_proyecto' => $clave_proyecto_insertado,
                        'fecha_agregado' => now(),
                    ]);
                    
                    // Notificar al asesor seleccionado
                    $asesorUser->notify(new AsesorActivityNotification(
                        $request->input('nombre'),         
                        $request->input('clave_proyecto'), // Usar la clave generada aquí para la notificación
                        $user->name,                       
                        route('asesor.proyectos.propuestas') 
                    ));
                }
            }
            
            return redirect()->route('home')->with('success', 'Propuesta de proyecto creada exitosamente y pendiente de revisión por el asesor.');

        } catch (\Exception $e) {
            // Manejo de errores: redirige de vuelta con los datos y el mensaje de error
            return redirect()->back()->withInput()->with('error', 'Error al crear la propuesta: ' . $e->getMessage());
        }
    }

    /**
     * Muestra una lista de propuestas de proyectos pendientes para revisión del asesor.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function listAdvisorProposals()
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        if (!$user->hasRole('asesor')) {
            return redirect()->route('home')->with('error', 'No tienes permiso para ver propuestas de asesor.');
        }

        // Buscar el ID de asesor en la tabla 'asesor' usando el email del usuario
        $asesor = DB::table('asesor')->where('correo_electronico', $user->email)->first();

        if (!$asesor) {
            return redirect()->route('home')->with('error', 'No se encontró la información de asesor para tu cuenta.');
        }

        $idAsesor = $asesor->idAsesor;

        // Ahora usamos el idAsesor correcto para filtrar las propuestas.
        $propuestas = DB::table('proyecto')
            ->join('asesor_proyecto', 'proyecto.clave_proyecto', '=', 'asesor_proyecto.clave_proyecto')
            ->leftJoin('categoria', 'proyecto.categoria', '=', 'categoria.idCategoria')
            ->leftJoin('tipo', 'proyecto.tipo', '=', 'tipo.idTipo')
            ->leftJoin('etapas', 'proyecto.etapa', '=', 'etapas.idEtapa')
            ->leftJoin('color', 'etapas.color', '=', 'color.nombre')
            ->select(
                'proyecto.clave_proyecto',
                'proyecto.nombre',
                'proyecto.descripcion',
                'categoria.nombre as nombre_categoria',
                'tipo.nombre as nombre_tipo',
                'etapas.nombre as nombre_etapa',
                'etapas.color as nombre_color_etapa',
                'color.clase as etapa_color_class',
                'proyecto.fecha_agregado',
                'proyecto.etapa',
                'proyecto.motivo_rechazo'
            )
            ->where('asesor_proyecto.idAsesor', $idAsesor)
            ->whereIn('proyecto.etapa', [self::ID_ETAPA_PENDIENTE_ASESOR])
            ->orderBy('fecha_agregado', 'desc')
            ->paginate(5);

        $titulo = "Revisión de Propuestas de Proyectos (Asesor)";

        return view('asesores.propuestas_revision', compact('propuestas', 'titulo'));
    }

    /**
     * El asesor revisa una propuesta (da Visto Bueno o rechaza).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $clave_proyecto
     * @return \Illuminate\Http\RedirectResponse
     */
public function reviewAdvisorProposal(Request $request, $clave_proyecto)
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        if (!$user->hasRole('asesor')) {
            return back()->with('error', 'No tienes permiso para realizar esta acción.');
        }

        // Buscar el ID del asesor en la tabla 'asesor' usando el email del usuario
        $asesor = DB::table('asesor')->where('correo_electronico', $user->email)->first();
        if (!$asesor) {
            return back()->with('error', 'No se encontró tu información de asesor. Contacta al administrador.');
        }

        $request->validate([
            'action' => 'required|in:accept,reject',
            'motivo_rechazo' => 'nullable|string|max:500',
        ]);
        
        $proyecto = DB::table('proyecto')
            ->join('asesor_proyecto', 'proyecto.clave_proyecto', '=', 'asesor_proyecto.clave_proyecto')
            ->where('asesor_proyecto.idAsesor', $asesor->idAsesor)
            ->where('proyecto.clave_proyecto', $clave_proyecto)
            ->first();

        if (!$proyecto || $proyecto->etapa != self::ID_ETAPA_PENDIENTE_ASESOR) {
            return back()->with('error', 'Propuesta no encontrada o no está pendiente para tu revisión.');
        }

        $updateData = [];
        $notificationMessageForLeader = '';
        $rejectionReason = null;

        if ($request->input('action') === 'accept') {
            $updateData = [
                'etapa' => self::ID_ETAPA_VISTO_BUENO_ASESOR,
                'motivo_rechazo' => null,
            ];
            $newStatus = 'Aprobada por el Asesor';

            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                // Notificación para el administrador cuando el asesor da el visto bueno
                $admin->notify(new NewProposalNotification(
                    $proyecto->nombre,
                    $proyecto->clave_proyecto,
                    $user->name,
                    route('admin.proyectos.propuestas')
                ));
            }
        } elseif ($request->input('action') === 'reject') {
            if (empty($request->input('motivo_rechazo'))) {
                return back()->with('error', 'El motivo de rechazo es obligatorio para rechazar la propuesta.');
            }
            $rejectionReason = $request->input('motivo_rechazo');
            $updateData = [
                'etapa' => self::ID_ETAPA_RECHAZADA,
                'motivo_rechazo' => $rejectionReason,
            ];
            $newStatus = 'Rechazada por el Asesor';
        }

        DB::table('proyecto')->where('clave_proyecto', $clave_proyecto)->update($updateData);

        $leader = DB::table('alumno_proyecto')
            ->where('clave_proyecto', $clave_proyecto)
            ->where('lider', 1)
            ->join('alumno', 'alumno_proyecto.no_control', '=', 'alumno.no_control')
            ->join('users', 'alumno.correo_institucional', '=', 'users.email')
            ->select('users.id')
            ->first();

        if ($leader) {
            $leaderUser = User::find($leader->id);
            if ($leaderUser) {
                // Notificación para el alumno líder sobre el estado de la propuesta
                $leaderUser->notify(new StudentProposalStatusNotification(
                    $proyecto->nombre,
                    $newStatus,
                    route('home'), // Se redirige al home, donde el alumno puede ver sus proyectos
                    $rejectionReason
                ));
            }
        }

        return back()->with('success', 'Propuesta de proyecto procesada exitosamente.');
    }


    /**
     * Muestra una lista de propuestas de proyectos pendientes o rechazadas para revisión del administrador.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function listProposals()
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        if (!$user->hasRole('admin')) {
            return redirect()->route('home')->with('error', 'No tienes permiso para ver propuestas de administrador.');
        }

        $propuestas = DB::table('proyecto')
            ->leftJoin('categoria', 'proyecto.categoria', '=', 'categoria.idCategoria')
            ->leftJoin('tipo', 'proyecto.tipo', '=', 'tipo.idTipo')
            ->leftJoin('etapas', 'proyecto.etapa', '=', 'etapas.idEtapa')
            ->leftJoin('color', 'etapas.color', '=', 'color.nombre')
            ->select(
                'proyecto.clave_proyecto',
                'proyecto.nombre',
                'proyecto.descripcion',
                'categoria.nombre as nombre_categoria',
                'tipo.nombre as nombre_tipo',
                'etapas.nombre as nombre_etapa',
                'etapas.color as nombre_color_etapa',
                'color.clase as etapa_color_class',
                'proyecto.fecha_agregado',
                'proyecto.etapa',
                'proyecto.motivo_rechazo'
            )
            ->whereIn('proyecto.etapa', [self::ID_ETAPA_VISTO_BUENO_ASESOR, self::ID_ETAPA_RECHAZADA])
            ->orderBy('fecha_agregado', 'desc')
            ->paginate(5);

        $titulo = "Revisión de Propuestas de Proyectos (Administrador)";

        return view('Admin.propuestas_proyectos', compact('propuestas', 'titulo'));
    }

    /**
     * El administrador revisa una propuesta (aprueba o rechaza).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $clave_proyecto
     * @return \Illuminate\Http\RedirectResponse
     */
public function reviewProposal(Request $request, $clave_proyecto)
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        if (!$user->hasRole('admin')) {
            return back()->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $request->validate([
            'action' => 'required|in:accept,reject',
            'motivo_rechazo' => 'nullable|string|max:500',
        ]);

        $proyecto = DB::table('proyecto')->where('clave_proyecto', $clave_proyecto)->first();

        if (!$proyecto || $proyecto->etapa != self::ID_ETAPA_VISTO_BUENO_ASESOR) {
            return back()->with('error', 'Propuesta no encontrada o no está pendiente para tu revisión.');
        }

        $updateData = [];
        $rejectionReason = null;

        if ($request->input('action') === 'accept') {
            $updateData = [
                'etapa' => self::ID_ETAPA_APROBADA_ADMIN,
                'motivo_rechazo' => null,
            ];
            $newStatus = 'Aprobada por el Administrador';
        } elseif ($request->input('action') === 'reject') {
            if (empty($request->input('motivo_rechazo'))) {
                return back()->with('error', 'El motivo de rechazo es obligatorio para rechazar la propuesta.');
            }
            $rejectionReason = $request->input('motivo_rechazo');
            $updateData = [
                'etapa' => self::ID_ETAPA_RECHAZADA,
                'motivo_rechazo' => $rejectionReason,
            ];
            $newStatus = 'Rechazada por el Administrador';
        }

        DB::table('proyecto')->where('clave_proyecto', $clave_proyecto)->update($updateData);

        $leader = DB::table('alumno_proyecto')
            ->where('clave_proyecto', $clave_proyecto)
            ->where('lider', 1)
            ->join('alumno', 'alumno_proyecto.no_control', '=', 'alumno.no_control')
            ->join('users', 'alumno.correo_institucional', '=', 'users.email')
            ->select('users.id')
            ->first();

        if ($leader) {
            $leaderUser = User::find($leader->id);
            if ($leaderUser) {
                // Notificación para el alumno líder sobre el estado de la propuesta
                $leaderUser->notify(new StudentProposalStatusNotification(
                    $proyecto->nombre,
                    $newStatus,
                    route('home'), // Se redirige al home, donde el alumno puede ver sus proyectos
                    $rejectionReason
                ));
            }
        }

        return back()->with('success', 'Propuesta de proyecto procesada exitosamente.');
    }
}
