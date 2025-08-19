<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\User; 
use App\Models\Alumno; 
use App\Models\Asesor; 
use App\Models\Mentor; 

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

        // No es estrictamente necesario cargar estos datos aquí si solo se usan en el modal,
        // pero se mantiene por si la vista 'auth.registro-datos' los necesita.
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
            return response()->json(['error' => 'Error interno del servidor al cargar datos del perfil: ' . $e->getMessage()], 500);
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
        $rules = [
            'name' => ['required', 'string', 'max:255'], // Regla general para el nombre en la tabla 'users'
        ];
        $messages = [];
        $redirectMessage = 'Datos actualizados exitosamente.';
        $tableToUpdate = null; // Almacenará el nombre de la tabla de perfil específica del rol
        $profileDataToUpdate = []; // Almacenará los datos para esa tabla

        if (!$user) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Usuario no autenticado.'], 401);
            }
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para completar esta acción.');
        }

        $userRole = $user->roles->first() ? $user->roles->first()->name : null;

        // Validaciones y datos específicos de cada rol
        if ($userRole === 'alumno') {
            $tableToUpdate = 'alumno';
            $rules = array_merge($rules, [
                'no_control' => ['required', 'string', 'max:8', Rule::unique('alumno', 'no_control')->ignore($user->email, 'correo_institucional')],
                'carrera' => ['required', 'string', Rule::exists('carrera', 'nombre')],
                'telefono' => ['required', 'string', 'max:10'],
                'semestre' => ['required', 'integer', 'min:1', 'max:10'],
            ]);
            $messages = array_merge($messages, [
                'no_control.unique' => 'Este número de control ya está registrado para otro alumno.',
                'carrera.exists' => 'La carrera seleccionada no es válida.',
            ]);
            $profileDataToUpdate = [
                'no_control' => $request->input('no_control'),
                'carrera' => $request->input('carrera'),
                'telefono' => $request->input('telefono'),
                'semestre' => $request->input('semestre'),
                'correo_institucional' => $user->email,
                'fecha_agregado' => now(),
            ];

        } elseif ($userRole === 'asesor') {
            $tableToUpdate = 'asesor';
            $rules = array_merge($rules, [
                'telefono' => ['required', 'string', 'max:10'],
            ]);
            $profileDataToUpdate = [
                'telefono' => $request->input('telefono'),
                'correo_electronico' => $user->email,
                'fecha_agregado' => now(),
            ];

        } elseif ($userRole === 'mentor') {
            $tableToUpdate = 'mentor';
            $rules = array_merge($rules, [
                'telefono' => ['required', 'string', 'max:10'],
            ]);
            $profileDataToUpdate = [
                'telefono' => $request->input('telefono'),
                'correo_electronico' => $user->email,
                'fecha_agregado' => now(),
            ];

        } elseif ($userRole === 'admin') {
            // No se requieren reglas adicionales para el administrador, ya que solo actualiza 'name' en 'users'.
            $redirectMessage = 'Datos de administrador actualizados correctamente.';
        } else {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Tu rol no requiere completar datos adicionales o no está configurado para ello.'], 400);
            }
            return redirect()->route('home')->with('error', 'Tu rol no requiere completar datos adicionales o no está configurado para ello.');
        }

        try {
            // Ejecutar la validación con las reglas y mensajes definidos
            $validatedData = $request->validate($rules, $messages);

            // Iniciar una transacción de base de datos para asegurar la consistencia
            DB::beginTransaction();

            // 1. Actualizar el nombre en la tabla 'users'
            $user->name = $validatedData['name'];
            $user->save(); // Guarda los cambios en el modelo de usuario

            // 2. Actualizar la tabla específica del rol si el usuario tiene un rol que requiere una tabla de perfil
            if ($tableToUpdate) {
                // Si el rol es alumno, asesor o mentor, su tabla de perfil tiene una columna 'nombre'
                if (in_array($userRole, ['alumno', 'asesor', 'mentor'])) {
                     $profileDataToUpdate['nombre'] = $validatedData['name']; // Sincroniza el nombre
                }
                
                // Actualizar o insertar el registro en la tabla de perfil del rol
                DB::table($tableToUpdate)->updateOrInsert(
                    [$this->getEmailColumnForRole($userRole) => $user->email],
                    $profileDataToUpdate
                );
            }

            // Confirmar la transacción
            DB::commit();

            // Respuesta para peticiones AJAX
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => $redirectMessage]);
            }
            // Redirección para peticiones no AJAX
            return redirect()->route('home')->with('success', $redirectMessage);

        } catch (ValidationException $e) {
            // Revertir la transacción si hay errores de validación
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Revertir la transacción si ocurre cualquier otra excepción
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error al guardar los datos: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Error al guardar los datos: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Helper para obtener el nombre de la columna de correo electrónico por rol.
     */
    protected function getEmailColumnForRole(string $roleName): string
    {
        switch ($roleName) {
            case 'alumno':
                return 'correo_institucional';
            case 'asesor':
            case 'mentor':
                return 'correo_electronico';
            default:
                return 'email'; // En caso de que se necesite para otros roles sin tabla específica.
        }
    }
}