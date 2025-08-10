<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // Asume que tienes un modelo User
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // Para interactuar con la tabla token_rol

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home'; // Cambia esto a tu ruta deseada después del registro

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'token' => [ // Reglas de validación para el token
                'nullable', // El token puede ser nulo si el usuario no ingresa uno
                'string',
                'exists:token_rol,token', // Verifica que el token exista en la tabla token_rol
                // Aquí puedes agregar una regla custom para verificar si el token ya fue usado
                function ($attribute, $value, $fail) {
                    $tokenRecord = DB::table('token_rol')->where('token', $value)->first();
                    if ($tokenRecord && DB::table('users')->where('email', $tokenRecord->correo)->exists()) {
                        $fail('Este token ya ha sido utilizado para registrar el correo: ' . $tokenRecord->correo);
                    }
                },
            ],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Lógica para crear el usuario
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Lógica para asignar rol
        // Si el token está presente y no está vacío, asigna el rol del token.
        if (isset($data['token']) && !empty($data['token'])) {
            $tokenRecord = DB::table('token_rol')->where('token', $data['token'])->first();

            if ($tokenRecord) {
                // Verificar que el correo del token coincida con el correo de registro
                if ($tokenRecord->correo !== $data['email']) {
                    // Si no coinciden, el usuario se registra pero sin el rol del token.
                    return $user;
                }

                // Asignar el rol al usuario usando el ID del token
                $rolName = DB::table('roles')->where('id', $tokenRecord->rol)->first()->name;
                
                // Asume que tu modelo User tiene el trait HasRoles (ej. de Spatie/Laravel-Permission)
                if (method_exists($user, 'assignRole')) {
                    $user->assignRole($rolName);
                }
                
                // Opcional: Eliminar el token después de usarlo
                DB::table('token_rol')->where('idToken', $tokenRecord->idToken)->delete();
            }
        } else {
            // Si NO hay token, asignar el rol 'alumno' por defecto
            // Asegúrate de que 'alumno' sea un rol válido en tu tabla 'roles'
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('alumno'); // <-- Asignación de rol por defecto
            }
        }
        
        return $user;
    }

    // Método para mostrar el formulario de registro (si Laravel UI no lo tiene)
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
}
