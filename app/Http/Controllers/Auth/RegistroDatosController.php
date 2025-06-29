<?php

namespace App\Http\Controllers\Auth; // Asegúrate de que el namespace sea el correcto, podría ser App\Http\Controllers

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Extiende de Controller
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // Para usar reglas de validación como Rule::exists

class RegistroDatosController extends Controller
{
    public function __construct()
    {
        // Solo usuarios autenticados y verificados pueden acceder a esta página
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Muestra el formulario de registro de datos específicos por rol.
     * Si el perfil ya está completo para el rol del usuario, redirige a /home.
     */
    public function index()
    {
        $user = Auth::user();
        $titulo = "Completar Registro de Datos";
        $data = []; // Contendrá los datos específicos del rol y la vista a cargar

        // Determinar el rol del usuario y cargar datos existentes si los hay
        if ($user->hasRole('alumno')) {
            $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();
            if ($alumno) {
                // Si el alumno ya completó sus datos, redirigir a home
                return redirect()->route('home')->with('info', 'Tu perfil de alumno ya está completo.');
            }
            // Si el alumno no ha completado sus datos, preparar variables para el formulario de alumno
            $data['rol_type'] = 'alumno'; // Para la lógica de la vista
            $data['user_data'] = $alumno; // Usaremos 'user_data' genérico para pasar los datos existentes (o null)
            $data['carreras'] = DB::table('carrera')->get(); // Necesitamos las carreras para el select
        } elseif ($user->hasRole('asesor')) {
            $asesor = DB::table('asesor')->where('correo_electronico', $user->email)->first();
            if ($asesor) {
                // Si el asesor ya completó sus datos, redirigir a home
                return redirect()->route('home')->with('info', 'Tu perfil de asesor ya está completo.');
            }
            // Si el asesor no ha completado sus datos, preparar variables para el formulario de asesor
            $data['rol_type'] = 'asesor';
            $data['user_data'] = $asesor; // Usaremos 'user_data' genérico
        } elseif ($user->hasRole('mentor')) {
            $mentor = DB::table('mentor')->where('correo_electronico', $user->email)->first();
            if ($mentor) {
                // Si el mentor ya completó sus datos, redirigir a home
                return redirect()->route('home')->with('info', 'Tu perfil de mentor ya está completo.');
            }
            // Si el mentor no ha completado sus datos, preparar variables para el formulario de mentor
            $data['rol_type'] = 'mentor';
            $data['user_data'] = $mentor; // Usaremos 'user_data' genérico
        } else {
            // Si el usuario tiene un rol que no requiere datos adicionales o un rol no manejado aquí,
            // redirigir a home y mostrar un mensaje informativo.
            return redirect()->route('home')->with('info', 'No se requieren datos adicionales para tu rol.');
        }

        // CAMBIO CLAVE AQUÍ: Especificar la carpeta 'auth' para la vista.
        return view('auth.registro-datos', compact('titulo', 'data'));
    }

    /**
     * Almacena los datos específicos del rol del usuario.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $rules = [];
        $messages = [];
        $redirectMessage = '';
        $tableToInsert = ''; // Tabla donde se insertarán los datos

        if ($user->hasRole('alumno')) {
            $tableToInsert = 'alumno';
            $rules = [
                'no_control' => ['required', 'string', 'max:8', Rule::unique('alumno', 'no_control')],
                'nombre' => ['required', 'string', 'max:50'],
                'carrera' => ['required', 'string', Rule::exists('carrera', 'nombre')], // Validar que la carrera exista
                'telefono' => ['required', 'string', 'max:10'],
                'semestre' => ['required', 'integer', 'min:1', 'max:10'],
            ];
            $messages = [
                'no_control.unique' => 'Este número de control ya está registrado para otro alumno.',
                'carrera.exists' => 'La carrera seleccionada no es válida.',
            ];
            $redirectMessage = 'Datos de alumno guardados exitosamente.';

            $dataToInsert = [
                'no_control' => $request->input('no_control'),
                'nombre' => $request->input('nombre'),
                'carrera' => $request->input('carrera'),
                'telefono' => $request->input('telefono'),
                'semestre' => $request->input('semestre'),
                'correo_institucional' => $user->email, // Asociar con el correo del usuario
                'fecha_agregado' => now(), // Columna timestamp
            ];

        } elseif ($user->hasRole('asesor')) {
            $tableToInsert = 'asesor';
            $rules = [
                'nombre' => ['required', 'string', 'max:50'],
                'telefono' => ['required', 'string', 'max:10'],
                // El correo_electronico se toma directamente del Auth::user()->email, no se valida aquí.
            ];
            $redirectMessage = 'Datos de asesor guardados exitosamente.';

            $dataToInsert = [
                'nombre' => $request->input('nombre'),
                'telefono' => $request->input('telefono'),
                'correo_electronico' => $user->email, // Asociar con el correo del usuario
                'fecha_agregado' => now(), // Columna timestamp
            ];

        } elseif ($user->hasRole('mentor')) {
            $tableToInsert = 'mentor';
            $rules = [
                'nombre' => ['required', 'string', 'max:50'],
                'telefono' => ['required', 'string', 'max:10'],
                // El correo_electronico se toma directamente del Auth::user()->email
            ];
            $redirectMessage = 'Datos de mentor guardados exitosamente.';

            $dataToInsert = [
                'nombre' => $request->input('nombre'),
                'telefono' => $request->input('telefono'),
                'correo_electronico' => $user->email, // Asociar con el correo del usuario
                'fecha_agregado' => now(), // Columna timestamp
            ];

        } else {
            // Rol no reconocido o que no requiere datos adicionales
            return redirect()->route('home')->with('error', 'Tu rol no requiere completar datos adicionales o no está configurado para ello.');
        }

        // Validar los datos de la solicitud
        $request->validate($rules, $messages);

        // Insertar los datos en la tabla correspondiente
        DB::table($tableToInsert)->insert($dataToInsert);

        // Redirigir al usuario a la página de inicio con un mensaje de éxito
        return redirect()->route('home')->with('success', $redirectMessage);
    }
}
