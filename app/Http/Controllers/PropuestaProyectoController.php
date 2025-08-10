<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AdminActivityNotification;
use App\Notifications\NewProposalNotification;
use App\Models\User;

class PropuestaProyectoController extends Controller
{
    // Constantes para las etapas de los proyectos
    const ID_ETAPA_PENDIENTE = 1;
    const ID_ETAPA_APROBADA = 3;
    const ID_ETAPA_RECHAZADA = 4;

    public function __construct()
    {
        // Asegura que solo usuarios autenticados puedan acceder a este controlador
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
        $user = Auth::user(); // Obtiene el usuario autenticado
        // Busca el registro del alumno asociado al correo del usuario
        $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();

        // Verifica si el usuario tiene el rol 'alumno' y si existe un registro de alumno asociado
        if (!$user->hasRole('alumno') || !$alumno) {
            return redirect()->route('home')->with('error', 'No tienes permiso para crear una propuesta de proyecto.');
        }

        // Obtiene las categorías, tipos y etapas desde la base de datos para los selectores del formulario
        $categorias = DB::table('categoria')->get();
        $tipos = DB::table('tipo')->get();
        $etapas = DB::table('etapas')->get();

        // Retorna la vista para crear propuestas con los datos necesarios
        return view('alumnos.crear_propuesta', compact('categorias', 'tipos', 'etapas'));
    }

    /**
     * Almacena una nueva propuesta de proyecto enviada por un alumno.
     * Realiza validaciones, inserta el proyecto y sus requerimientos/resultados,
     * y notifica a los administradores.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeProposal(Request $request)
    {
        /** @var \App\Models\User */
        $user = Auth::user(); // Obtiene el usuario autenticado
        // Busca el registro del alumno asociado al correo del usuario
        $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();

        // Verifica si el usuario tiene el rol 'alumno' y si existe un registro de alumno asociado
        if (!$user->hasRole('alumno') || !$alumno) {
            return redirect()->route('home')->with('error', 'Acceso no autorizado para crear propuestas.');
        }

        // Si el campo de video está vacío, lo establece como null para evitar errores de validación de URL
        if (empty($request->input('video'))) {
            $request->merge(['video' => null]);
        }

        // Define las reglas de validación para los datos de la propuesta
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
            // Validaciones para los arrays de requerimientos y resultados
            'requerimientos' => 'nullable|array',
            'requerimientos.*.descripcion' => 'required_with:requerimientos.*.cantidad|string|max:100',
            'requerimientos.*.cantidad' => 'required_with:requerimientos.*.descripcion|string|max:50',
            'resultados' => 'nullable|array',
            'resultados.*.descripcion' => 'required|string|max:100',
        ]);

        try {
            // Inserta el nuevo proyecto en la tabla 'proyecto' con la etapa "pendiente"
            DB::table('proyecto')->insert([
                'clave_proyecto' => $request->input('clave_proyecto'),
                'nombre' => $request->input('nombre'),
                'nombre_descriptivo' => $request->input('nombre_descriptivo'),
                'descripcion' => $request->input('descripcion'),
                'categoria' => $request->input('categoria'),
                'tipo' => $request->input('tipo'),
                'etapa' => self::ID_ETAPA_PENDIENTE, // Asigna la etapa "Pendiente" por defecto
                'video' => $request->input('video'),
                'area_aplicacion' => $request->input('area_aplicacion'),
                'naturaleza_tecnica' => $request->input('naturaleza_tecnica'),
                'objetivo' => $request->input('objetivo'),
                'fecha_agregado' => now(), // Registra la fecha de creación
            ]);

            $clave_proyecto_insertado = $request->input('clave_proyecto');

            // Inserta los requerimientos asociados al proyecto
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

            // Inserta los resultados asociados al proyecto
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

            // Asocia al alumno actual como líder del proyecto recién creado
            DB::table('alumno_proyecto')->insert([
                'no_control' => $alumno->no_control,
                'clave_proyecto' => $clave_proyecto_insertado,
                'lider' => 1, // Marca al alumno como líder del proyecto
            ]);

            // Notifica a todos los administradores sobre la nueva propuesta
            $admins = User::role('admin')->get(); // Obtiene todos los usuarios con el rol 'admin'
            $proposalLink = route('admin.proyectos.propuestas'); // Define el enlace a la vista de propuestas

            foreach ($admins as $admin) {
                $admin->notify(new NewProposalNotification(
                    $request->input('nombre'),         // Nombre del proyecto
                    $request->input('clave_proyecto'), // Clave del proyecto
                    $user->name,                       // Nombre del usuario que envía la propuesta
                    $proposalLink                      // Enlace a la vista de propuestas
                ));
            }

            // Redirige al inicio con un mensaje de éxito
            return redirect()->route('home')->with('success', 'Propuesta de proyecto creada exitosamente y pendiente de revisión.');

        } catch (\Exception $e) {
            // Maneja cualquier error que ocurra durante el proceso de almacenamiento
            return redirect()->back()->withInput()->with('error', 'Error al crear la propuesta: ' . $e->getMessage());
        }
    }

    /**
     * Muestra una lista de propuestas de proyectos pendientes o rechazadas para revisión del administrador.
     *
     * @return \Illuminate\View\View
     */
    public function listProposals()
    {
        // Obtiene las propuestas de proyecto que están en etapa Pendiente o Rechazada, con paginación
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
            ->whereIn('proyecto.etapa', [self::ID_ETAPA_PENDIENTE, self::ID_ETAPA_RECHAZADA])
            ->orderBy('fecha_agregado', 'desc')
            ->paginate(5); // <-- Paginar por 5 elementos

        $titulo = "Revisión de Propuestas de Proyectos";

        // Retorna la vista con la lista de propuestas
        return view('Admin.propuestas_proyectos', compact('propuestas', 'titulo'));
    }

    /**
     * Procesa la revisión de una propuesta de proyecto (aceptar o rechazar).
     * Notifica al líder del proyecto sobre el resultado de la revisión.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $clave_proyecto La clave del proyecto a revisar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reviewProposal(Request $request, $clave_proyecto)
    {
        // Valida la acción (aceptar/rechazar) y el motivo de rechazo si aplica
        $request->validate([
            'action' => 'required|in:accept,reject',
            'motivo_rechazo' => 'nullable|string|max:500',
        ]);

        // Busca el proyecto por su clave
        $proyecto = DB::table('proyecto')->where('clave_proyecto', $clave_proyecto)->first();

        // Si el proyecto no se encuentra, redirige con un error
        if (!$proyecto) {
            return back()->with('error', 'Propuesta de proyecto no encontrada.');
        }

        $updateData = [];
        $notificationMessage = '';
        $notificationType = '';
        $motivoRechazo = null;

        // Lógica para aceptar la propuesta
        if ($request->input('action') === 'accept') {
            $updateData = [
                'etapa' => self::ID_ETAPA_APROBADA, // Cambia la etapa a "Aprobada"
                'motivo_rechazo' => null,           // Limpia el motivo de rechazo
            ];
            $notificationMessage = 'La propuesta de proyecto "' . $proyecto->nombre . '" ha sido APROBADA.';
            $notificationType = 'proposal_approved';
        } elseif ($request->input('action') === 'reject') {
            // Lógica para rechazar la propuesta
            if (empty($request->input('motivo_rechazo'))) {
                return back()->with('error', 'El motivo de rechazo es obligatorio para rechazar la propuesta.');
            }
            $motivoRechazo = $request->input('motivo_rechazo');
            $updateData = [
                'etapa' => self::ID_ETAPA_RECHAZADA, // Cambia la etapa a "Rechazada"
                'motivo_rechazo' => $motivoRechazo,  // Guarda el motivo de rechazo
            ];
            $notificationMessage = 'La propuesta de proyecto "' . $proyecto->nombre . '" ha sido RECHAZADA. Motivo: ' . $motivoRechazo;
            $notificationType = 'proposal_rejected';
        }

        // Actualiza el estado del proyecto en la base de datos
        DB::table('proyecto')->where('clave_proyecto', $clave_proyecto)->update($updateData);

        // Busca al líder del proyecto para enviarle una notificación
        $leader = DB::table('alumno_proyecto')
            ->where('clave_proyecto', $clave_proyecto)
            ->where('lider', 1)
            ->join('alumno', 'alumno_proyecto.no_control', '=', 'alumno.no_control')
            ->join('users', 'alumno.correo_institucional', '=', 'users.email')
            ->select('users.id')
            ->first();

        // Si se encuentra un líder, le envía la notificación
        if ($leader) {
            $leaderUser = User::find($leader->id);
            if ($leaderUser) {
                $leaderUser->notify(new AdminActivityNotification(
                    $notificationMessage,
                    route('home'), // Redirige al home del líder después de ver la notificación
                    $notificationType
                ));
            }
        }

        // Redirige con un mensaje de éxito
        return back()->with('success', 'Propuesta de proyecto procesada exitosamente.');
    }
}
