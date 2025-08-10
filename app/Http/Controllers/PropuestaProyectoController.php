<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AdminActivityNotification;
use App\Notifications\NewProposalNotification;
use App\Notifications\ProposalToAdvisorNotification; // <-- ¡NUEVO! Notificación para el asesor
use App\Models\User;

class PropuestaProyectoController extends Controller
{
    // Constantes para las etapas de los proyectos
    const ID_ETAPA_PENDIENTE_ASESOR = 1; // Ahora, pendiente significa pendiente para el asesor
    const ID_ETAPA_VISTO_BUENO_ASESOR = 2; // Visto bueno del asesor
    const ID_ETAPA_APROBADA_ADMIN = 3;   // Visto bueno del administrador (Aprobada)
    const ID_ETAPA_RECHAZADA = 4;        // Rechazada

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra el formulario para que un alumno cree una nueva propuesta de proyecto.
     * Requiere que el usuario esté autenticado y tenga el rol 'alumno'.
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
        $etapas = DB::table('etapas')->get();

        return view('alumnos.crear_propuesta', compact('categorias', 'tipos', 'etapas'));
    }

    /**
     * Almacena una nueva propuesta de proyecto enviada por un alumno.
     * Ahora la propuesta se envía a PENDIENTE_ASESOR y notifica al asesor.
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

        if (empty($request->input('video'))) {
            $request->merge(['video' => null]);
        }

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
            'requerimientos' => 'nullable|array',
            'requerimientos.*.descripcion' => 'required_with:requerimientos.*.cantidad|string|max:100',
            'requerimientos.*.cantidad' => 'required_with:requerimientos.*.descripcion|string|max:50',
            'resultados' => 'nullable|array',
            'resultados.*.descripcion' => 'required|string|max:100',
        ]);

        try {
            DB::table('proyecto')->insert([
                'clave_proyecto' => $request->input('clave_proyecto'),
                'nombre' => $request->input('nombre'),
                'nombre_descriptivo' => $request->input('nombre_descriptivo'),
                'descripcion' => $request->input('descripcion'),
                'categoria' => $request->input('categoria'),
                'tipo' => $request->input('tipo'),
                'etapa' => self::ID_ETAPA_PENDIENTE_ASESOR, // <-- Ahora pasa al asesor primero
                'video' => $request->input('video'),
                'area_aplicacion' => $request->input('area_aplicacion'),
                'naturaleza_tecnica' => $request->input('naturaleza_tecnica'),
                'objetivo' => $request->input('objetivo'),
                'fecha_agregado' => now(),
            ]);

            $clave_proyecto_insertado = $request->input('clave_proyecto');

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

            DB::table('alumno_proyecto')->insert([
                'no_control' => $alumno->no_control,
                'clave_proyecto' => $clave_proyecto_insertado,
                'lider' => 1,
            ]);

            // NOTIFICAR AL ASESOR (NO al administrador en este punto)
            // Primero, necesitamos saber quién es el asesor del alumno.
            // Asumo que tienes una relación entre alumno y asesor o que el alumno tiene un asesor asignado.
            // Si el alumno aún no tiene un asesor asignado en este punto, esta parte necesitará más lógica.
            // Por ahora, asumiré que el alumno está asociado a un asesor o que hay asesores que revisan todas las propuestas pendientes.
            // Para simplificar, buscaremos asesores aleatorios o los que podrían estar relacionados.
            // Si el alumno está asignado a un proyecto con un asesor, o tiene un asesor directo:
            $asesores = User::role('asesor')->get(); // Obtener todos los usuarios con rol 'asesor'

            foreach ($asesores as $asesor) {
                // NOTA: Aquí deberías implementar una lógica más fina para notificar
                // solo al asesor(es) relevante(s) para este proyecto o alumno.
                // Por simplicidad, se notifica a todos los asesores para el ejemplo.
                $asesor->notify(new ProposalToAdvisorNotification(
                    $request->input('nombre'),         // Nombre del proyecto
                    $request->input('clave_proyecto'), // Clave del proyecto
                    $user->name,                       // Nombre del alumno
                    route('asesor.proyectos.propuestas') // Ruta para que el asesor revise
                ));
            }


            return redirect()->route('home')->with('success', 'Propuesta de proyecto creada exitosamente y pendiente de revisión por el asesor.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al crear la propuesta: ' . $e->getMessage());
        }
    }

    /**
     * Muestra una lista de propuestas de proyectos pendientes para revisión del asesor.
     *
     * @return \Illuminate\View\View
     */
    public function listAdvisorProposals()
    {
        // Solo los asesores pueden ver estas propuestas
        /** @var \App\Models\User */
        $user = Auth::user();
        if (!$user->hasRole('asesor')) {
            return redirect()->route('home')->with('error', 'No tienes permiso para ver propuestas de asesor.');
        }

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
                'etapas.nombre as nombre_etapa',
                'proyecto.fecha_agregado',
                'proyecto.etapa',
                'proyecto.motivo_rechazo'
            )
            ->whereIn('proyecto.etapa', [self::ID_ETAPA_PENDIENTE_ASESOR]) // Solo propuestas pendientes para el asesor
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

        $request->validate([
            'action' => 'required|in:accept,reject',
            'motivo_rechazo' => 'nullable|string|max:500',
        ]);

        $proyecto = DB::table('proyecto')->where('clave_proyecto', $clave_proyecto)->first();

        if (!$proyecto || $proyecto->etapa != self::ID_ETAPA_PENDIENTE_ASESOR) {
            return back()->with('error', 'Propuesta no encontrada o no está pendiente para tu revisión.');
        }

        $updateData = [];
        $notificationMessageForLeader = '';
        $notificationMessageForAdmin = '';
        $notificationTypeForLeader = '';

        if ($request->input('action') === 'accept') {
            $updateData = [
                'etapa' => self::ID_ETAPA_VISTO_BUENO_ASESOR, // <-- Cambia a "Visto Bueno Asesor"
                'motivo_rechazo' => null,
            ];
            $notificationMessageForLeader = 'Tu propuesta "' . $proyecto->nombre . '" ha recibido el Visto Bueno del asesor y ahora está PENDIENTE DE REVISIÓN del administrador.';
            $notificationTypeForLeader = 'proposal_advisor_approved';

            // Notificar a los administradores que una propuesta recibió el Visto Bueno del asesor
            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                $notificationMessageForAdmin = 'La propuesta "' . $proyecto->nombre . '" ha recibido el Visto Bueno del asesor y está lista para tu revisión.';
                $admin->notify(new NewProposalNotification( // Reutilizamos NewProposalNotification si es genérica para admins
                    $proyecto->nombre,
                    $proyecto->clave_proyecto,
                    $user->name, // Nombre del asesor que dio el VB
                    route('admin.proyectos.propuestas') // Ruta para el admin
                ));
            }

        } elseif ($request->input('action') === 'reject') {
            if (empty($request->input('motivo_rechazo'))) {
                return back()->with('error', 'El motivo de rechazo es obligatorio para rechazar la propuesta.');
            }
            $motivoRechazo = $request->input('motivo_rechazo');
            $updateData = [
                'etapa' => self::ID_ETAPA_RECHAZADA, // <-- Se rechaza directamente
                'motivo_rechazo' => $motivoRechazo,
            ];
            $notificationMessageForLeader = 'Tu propuesta "' . $proyecto->nombre . '" ha sido RECHAZADA por el asesor. Motivo: ' . $motivoRechazo;
            $notificationTypeForLeader = 'proposal_advisor_rejected';
        }

        DB::table('proyecto')->where('clave_proyecto', $clave_proyecto)->update($updateData);

        // Notificar al líder del proyecto sobre la decisión del asesor
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
                $leaderUser->notify(new AdminActivityNotification( // Reutilizamos AdminActivityNotification si es genérica
                    $notificationMessageForLeader,
                    route('home'), // Redirige al home del líder
                    $notificationTypeForLeader
                ));
            }
        }

        return back()->with('success', 'Propuesta de proyecto procesada exitosamente.');
    }


    /**
     * Muestra una lista de propuestas de proyectos pendientes o rechazadas para revisión del administrador.
     * Ahora solo muestra las que tienen Visto Bueno del asesor o fueron rechazadas (final).
     *
     * @return \Illuminate\View\View
     */
    public function listProposals()
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        if (!$user->hasRole('admin')) {
            return redirect()->route('home')->with('error', 'No tienes permiso para ver propuestas de administrador.');
        }

        // El administrador ahora ve las propuestas que ya tienen el Visto Bueno del asesor
        // o que han sido rechazadas (finalmente)
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
                'etapas.nombre as nombre_etapa',
                'proyecto.fecha_agregado',
                'proyecto.etapa',
                'proyecto.motivo_rechazo'
            )
            ->whereIn('proyecto.etapa', [self::ID_ETAPA_VISTO_BUENO_ASESOR, self::ID_ETAPA_RECHAZADA]) // <-- CAMBIO
            ->orderBy('fecha_agregado', 'desc')
            ->paginate(5);

        $titulo = "Revisión de Propuestas de Proyectos (Administrador)"; // Título más específico

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

        // El administrador solo puede revisar propuestas que tienen el Visto Bueno del asesor
        if (!$proyecto || $proyecto->etapa != self::ID_ETAPA_VISTO_BUENO_ASESOR) {
            return back()->with('error', 'Propuesta no encontrada o no está pendiente para tu revisión.');
        }

        $updateData = [];
        $notificationMessage = '';
        $notificationType = '';
        $motivoRechazo = null;

        if ($request->input('action') === 'accept') {
            $updateData = [
                'etapa' => self::ID_ETAPA_APROBADA_ADMIN, // <-- Cambia a "Aprobada por Admin"
                'motivo_rechazo' => null,
            ];
            $notificationMessage = 'La propuesta de proyecto "' . $proyecto->nombre . '" ha sido APROBADA por el administrador.';
            $notificationType = 'proposal_approved_admin';
        } elseif ($request->input('action') === 'reject') {
            if (empty($request->input('motivo_rechazo'))) {
                return back()->with('error', 'El motivo de rechazo es obligatorio para rechazar la propuesta.');
            }
            $motivoRechazo = $request->input('motivo_rechazo');
            $updateData = [
                'etapa' => self::ID_ETAPA_RECHAZADA, // <-- Se rechaza
                'motivo_rechazo' => $motivoRechazo,
            ];
            $notificationMessage = 'La propuesta de proyecto "' . $proyecto->nombre . '" ha sido RECHAZADA por el administrador. Motivo: ' . $motivoRechazo;
            $notificationType = 'proposal_rejected_admin';
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
                $leaderUser->notify(new AdminActivityNotification(
                    $notificationMessage,
                    route('home'),
                    $notificationType
                ));
            }
        }

        return back()->with('success', 'Propuesta de proyecto procesada exitosamente.');
    }
}
