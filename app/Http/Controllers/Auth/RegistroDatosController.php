<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RegistroDatosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra el formulario de registro de datos específicos por rol.
     * Esta función ahora solo devuelve la vista, la lógica de pre-llenado
     * se manejará en el JavaScript del modal.
     */
    public function index()
    {
        /** @var \App\Models\User */

        $user = Auth::user();
        $titulo = "Completar Registro de Datos";
        $data = [];

        if ($user->hasRole('alumno')) {
            $data['rol_type'] = 'alumno';
            $data['carreras'] = DB::table('carrera')->get();
        } elseif ($user->hasRole('asesor')) {
            $data['rol_type'] = 'asesor';
        } elseif ($user->hasRole('mentor')) {
            $data['rol_type'] = 'mentor';
        } else {
            return redirect()->route('home')->with('info', 'No se requieren datos adicionales para tu rol.');
        }

        return view('auth.registro-datos', compact('titulo', 'data'));
    }

    /**
     * Obtiene los datos del perfil del usuario autenticado para pre-llenar el modal.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserProfileData(Request $request)
    {
        /** @var \App\Models\User */

        $user = Auth::user();
        $profileData = null;
        $roleType = null;
        $carreras = [];

        if (!$user) {
            // Si por alguna razón el usuario no está autenticado (aunque el middleware debería evitarlo)
            return response()->json(['error' => 'Usuario no autenticado.'], 401);
        }

        try {
            if ($user->hasRole('alumno')) {
                $profileData = DB::table('alumno')->where('correo_institucional', $user->email)->first();
                $roleType = 'alumno';
                // Asegúrate de que 'nombre' es el nombre de la columna en tu tabla 'carrera'
                $carreras = DB::table('carrera')->pluck('nombre')->toArray();
            } elseif ($user->hasRole('asesor')) {
                $profileData = DB::table('asesor')->where('correo_electronico', $user->email)->first();
                $roleType = 'asesor';
            } elseif ($user->hasRole('mentor')) {
                $profileData = DB::table('mentor')->where('correo_electronico', $user->email)->first();
                $roleType = 'mentor';
            } else {
                // Si el usuario no tiene ninguno de los roles esperados
                $roleType = 'otro'; // O un valor que indique que no hay datos específicos
            }

            return response()->json([
                'user_role' => $roleType,
                'profile_data' => $profileData,
                'carreras' => $carreras,
                'user_name' => $user->name,
                'user_email' => $user->email,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error interno del servidor al cargar datos del perfil.'], 500);
        }
    }

    /**
     * Almacena o actualiza los datos específicos del rol del usuario.
     * Responde con JSON si es una petición AJAX.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User */

        $user = Auth::user();
        $rules = [];
        $messages = [];
        $redirectMessage = '';
        $tableToInsert = '';
        $dataToInsert = [];

        if (!$user) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Usuario no autenticado.'], 401);
            }
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para completar esta acción.');
        }

        if ($user->hasRole('alumno')) {
            $tableToInsert = 'alumno';
            $rules = [
                'no_control' => ['required', 'string', 'max:8', Rule::unique('alumno', 'no_control')->ignore($user->email, 'correo_institucional')],
                'nombre' => ['required', 'string', 'max:50'],
                'carrera' => ['required', 'string', Rule::exists('carrera', 'nombre')],
                'telefono' => ['required', 'string', 'max:10'],
                'semestre' => ['required', 'integer', 'min:1', 'max:10'],
            ];
            $messages = [
                'no_control.unique' => 'Este número de control ya está registrado para otro alumno.',
                'carrera.exists' => 'La carrera seleccionada no es válida.',
            ];
            $redirectMessage = 'Datos de alumno guardados/actualizados exitosamente.';

            $dataToInsert = [
                'no_control' => $request->input('no_control'),
                'nombre' => $request->input('nombre'),
                'carrera' => $request->input('carrera'),
                'telefono' => $request->input('telefono'),
                'semestre' => $request->input('semestre'),
                'correo_institucional' => $user->email,
                'fecha_agregado' => now(),
            ];

        } elseif ($user->hasRole('asesor')) {
            $tableToInsert = 'asesor';
            $rules = [
                'nombre' => ['required', 'string', 'max:50'],
                'telefono' => ['required', 'string', 'max:10'],
            ];
            $redirectMessage = 'Datos de asesor guardados/actualizados exitosamente.';

            $dataToInsert = [
                'nombre' => $request->input('nombre'),
                'telefono' => $request->input('telefono'),
                'correo_electronico' => $user->email,
                'fecha_agregado' => now(),
            ];

        } elseif ($user->hasRole('mentor')) {
            $tableToInsert = 'mentor';
            $rules = [
                'nombre' => ['required', 'string', 'max:50'],
                'telefono' => ['required', 'string', 'max:10'],
            ];
            $redirectMessage = 'Datos de mentor guardados/actualizados exitosamente.';

            $dataToInsert = [
                'nombre' => $request->input('nombre'),
                'telefono' => $request->input('telefono'),
                'correo_electronico' => $user->email,
                'fecha_agregado' => now(),
            ];

        } else {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Tu rol no requiere completar datos adicionales o no está configurado para ello.'], 400);
            }
            return redirect()->route('home')->with('error', 'Tu rol no requiere completar datos adicionales o no está configurado para ello.');
        }

        try {
            $request->validate($rules, $messages);

            DB::table($tableToInsert)->updateOrInsert(
                [$this->getEmailColumnForRole($user->roles->first()->name) => $user->email],
                $dataToInsert
            );

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => $redirectMessage]);
            }
            return redirect()->route('home')->with('success', $redirectMessage);

        } catch (ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error al guardar los datos: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Error al guardar los datos: ' . $e->getMessage())->withInput();
        }
    }

    protected function getEmailColumnForRole(string $roleName): string
    {
        switch ($roleName) {
            case 'alumno':
                return 'correo_institucional';
            case 'asesor':
            case 'mentor':
                return 'correo_electronico';
            default:
                return 'email';
        }
    }
}
