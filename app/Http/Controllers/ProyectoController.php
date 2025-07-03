<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AdminActivityNotification;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf; // Importa la Facade de Dompdf

class ProyectoController extends Controller
{
    const ID_ETAPA_PENDIENTE = 1;
    const ID_ETAPA_APROBADA = 3;
    const ID_ETAPA_RECHAZADA = 4;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // CORRECCIÓN: Eliminar los filtros para mostrar todos los proyectos en el CRUD principal
        $proyectos = DB::table('proyecto')
                        ->orderBy('fecha_agregado', 'desc')
                        ->get();

        $columnas = ['Clave', 'Nombre', 'Nombre descriptivo', 'Categoria', 'Tipo', 'Etapa', 'Video', 'Registrado'];
        $categorias = DB::table('categoria')->get();
        $alumnos = DB::table('alumno')->get(); // Mantengo esta línea si se usa en la vista, si no, puedes quitarla.
        $tipos = DB::table('tipo')->get();
        $etapas = DB::table('etapas')->get();
        $total_registros = $proyectos->count();

        $titulo = "CRUD Proyectos";

        return view('Admin.cruds.proyectos', compact('proyectos', 'titulo', 'columnas', 'total_registros', 'categorias', 'alumnos', 'tipos', 'etapas'));
    }

    public function registro_existe($clave_proyecto)
    {
        $existe = DB::table('proyecto')->where('clave_proyecto', '=', $clave_proyecto)->count();
        return $existe > 0;
    }

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
                'objetivo' => $objetivo,
                'fecha_agregado' => now(),
            ]);

            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new AdminActivityNotification('Proyecto "' . $nombre . '" agregado directamente por administrador.', '/c_proyectos', 'proyecto_agregado_admin'));
            }

            return back()->with('success', 'Proyecto agregado exitosamente.');
        }
    }

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

            $admins = User::role('admin')->get();
            $updaterName = Auth::user()->name;
            $adminMessage = 'Proyecto "' . $nombre . '" actualizado por un administrador.';
            foreach ($admins as $admin) {
                $admin->notify(new AdminActivityNotification($adminMessage, '/c_proyectos', 'proyecto_actualizado_admin'));
            }

            return back()->with('success', 'Proyecto actualizado exitosamente.');
        }
    }

    public function eliminar(Request $request)
    {
        $clave_proyecto = $request->input('clave_proyecto_eliminar');
        $proyecto_eliminado = DB::table('proyecto')->where('clave_proyecto', '=', $clave_proyecto)->first();

        DB::table('proyecto')->where('clave_proyecto', '=', $clave_proyecto)->delete();

        if ($proyecto_eliminado) {
            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new AdminActivityNotification('Proyecto "' . $proyecto_eliminado->nombre . '" (Clave: ' . $clave_proyecto . ') eliminado.', '/c_proyectos', 'proyecto_eliminado_admin'));
            }
        }

        return back()->with('success', 'Proyecto eliminado exitosamente.');
    }

    public function eliminarMultiple(Request $request)
    {
        $proyectos_a_eliminar = $request->input('proyectos_seleccionados', []);

        if (empty($proyectos_a_eliminar)) {
            return back()->with('error', 'No se seleccionaron proyectos para eliminar.');
        }

        $nombres_proyectos = DB::table('proyecto')->whereIn('clave_proyecto', $proyectos_a_eliminar)->pluck('nombre')->implode(', ');

        DB::table('proyecto')->whereIn('clave_proyecto', $proyectos_a_eliminar)->delete();

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

            $admins = User::role('admin')->get();
            $updaterName = Auth::user()->name;
            $adminMessage = 'Proyecto "' . $request->input('nombre') . '" (Clave: ' . $clave_proyecto . ') actualizado por ' . $updaterName . '.';
            foreach ($admins as $admin) {
                $admin->notify(new AdminActivityNotification($adminMessage, '/proyectos/' . $clave_proyecto . '/editar', 'proyecto_actualizado_lider'));
            }

            return redirect()->route('home')->with('success', 'Proyecto actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el proyecto: ' . $e->getMessage());
        }
    }

    public function createProposalForm()
    {
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

    public function storeProposal(Request $request)
    {
        $user = Auth::user();
        $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();

        if (!$user->hasRole('alumno') || !$alumno) {
            return redirect()->route('home')->with('error', 'Acceso no autorizado para crear propuestas.');
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
        ]);

        if (empty($request->input('video'))) {
            $request->merge(['video' => null]);
        }

        try {
            DB::table('proyecto')->insert([
                'clave_proyecto' => $request->input('clave_proyecto'),
                'nombre' => $request->input('nombre'),
                'nombre_descriptivo' => $request->input('nombre_descriptivo'),
                'descripcion' => $request->input('descripcion'),
                'categoria' => $request->input('categoria'),
                'tipo' => $request->input('tipo'),
                'etapa' => self::ID_ETAPA_PENDIENTE,
                'video' => $request->input('video'),
                'area_aplicacion' => $request->input('area_aplicacion'),
                'naturaleza_tecnica' => $request->input('naturaleza_tecnica'),
                'objetivo' => $request->input('objetivo'),
                'fecha_agregado' => now(),
            ]);

            DB::table('alumno_proyecto')->insert([
                'no_control' => $alumno->no_control,
                'clave_proyecto' => $request->input('clave_proyecto'),
                'lider' => 1,
            ]);

            $admins = User::role('admin')->get();
            $proposalLink = route('admin.proyectos.propuestas');
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

    public function listProposals()
    {
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
                        ->get();

        $titulo = "Revisión de Propuestas de Proyectos";

        return view('Admin.propuestas_proyectos', compact('propuestas', 'titulo'));
    }

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
                'etapa' => self::ID_ETAPA_APROBADA,
                'motivo_rechazo' => null,
            ];
            $notificationMessage = 'La propuesta de proyecto "' . $proyecto->nombre . '" ha sido APROBADA.';
            $notificationType = 'proposal_approved';
        } elseif ($request->input('action') === 'reject') {
            if (empty($request->input('motivo_rechazo'))) {
                return back()->with('error', 'El motivo de rechazo es obligatorio para rechazar la propuesta.');
            }
            $motivoRechazo = $request->input('motivo_rechazo');
            $updateData = [
                'etapa' => self::ID_ETAPA_RECHAZADA,
                'motivo_rechazo' => $motivoRechazo,
            ];
            $notificationMessage = 'La propuesta de proyecto "' . $proyecto->nombre . '" ha sido RECHAZADA. Motivo: ' . $motivoRechazo;
            $notificationType = 'proposal_rejected';
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

    /**
     * Genera la ficha técnica de un proyecto en formato PDF.
     * Utiliza la vista 'layouts.pdf' y permite la visualización en el navegador.
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

        // Obtener los resultados del proyecto (asumiendo tabla 'proyecto_resultados')
        $resultados = DB::table('proyecto_resultados')
                            ->where('clave_proyecto', $clave_proyecto)
                            ->get();

        // Obtener los alumnos asociados al proyecto con su carrera
        $alumno_proyecto = DB::table('alumno_proyecto')
                                ->where('clave_proyecto', $clave_proyecto)
                                ->join('alumno', 'alumno_proyecto.no_control', '=', 'alumno.no_control')
                                // CORRECCIÓN: Unir por el nombre de la carrera, no por un ID que no existe
                                ->leftJoin('carrera', 'alumno.carrera', '=', 'carrera.nombre')
                                ->select('alumno.*', 'carrera.nombre as carrera_nombre_completo', 'alumno_proyecto.lider')
                                ->get();

        // Obtener los asesores asociados al proyecto
        $asesor_proyecto = DB::table('asesor_proyecto')
                                ->where('clave_proyecto', $clave_proyecto)
                                ->join('asesor', 'asesor_proyecto.idAsesor', '=', 'asesor.idAsesor')
                                ->select('asesor.*')
                                ->get();

        // Obtener los requerimientos del proyecto (asumiendo tabla 'proyecto_requerimientos')
        $requerimientos = DB::table('proyecto_requerimientos')
                                ->where('clave_proyecto', $clave_proyecto)
                                ->get();

        // Pasar todos los datos a la vista del PDF
        $data = [
            'proyecto' => $proyecto,
            'resultados' => $resultados,
            'alumno_proyecto' => $alumno_proyecto,
            'asesor_proyecto' => $asesor_proyecto,
            'requerimientos' => $requerimientos,
            // Las siguientes colecciones se pasan porque tu layouts/pdf.blade.php las usa
            // aunque para la categoría del proyecto principal se usará $proyecto->nombre_categoria
            'categorias' => DB::table('categoria')->get(), // Necesario si otras partes del PDF lo usan
            'carreras' => DB::table('carrera')->get(),   // Necesario si otras partes del PDF lo usan
        ];

        // Cargar la vista 'layouts.pdf' y generar el PDF
        $pdf = Pdf::loadView('layouts.pdf', $data);

        // Opcional: Configurar el tamaño del papel y la orientación
        // $pdf->setPaper('A4', 'portrait');

        // Visualizar el PDF en el navegador
        return $pdf->stream('ficha_tecnica_' . $clave_proyecto . '.pdf');
    }
}
