<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User; // Importa el modelo User
use Spatie\Permission\Models\Role; // Importa el modelo Role de Spatie
use Illuminate\Support\Facades\Hash; // Para hashear contraseñas

class UsuarioController extends Controller
{
    public function index()
    {
        // Carga los usuarios y sus roles eager-loaded para Spatie
        $users = User::with('roles')->orderByDesc('created_at')->get();

        // Mapea los usuarios para incluir el nombre del rol principal
        // Asume que un usuario solo tendrá un rol para mostrar en el CRUD,
        // si tiene múltiples, se mostrará el primero.
        $usuarios = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                // Obtiene el nombre del primer rol asignado, si existe.
                // Si el usuario no tiene rol, será 'N/A' o el valor predeterminado que quieras.
                'role_name' => $user->roles->first() ? $user->roles->first()->name : 'Sin Rol'
            ];
        });

        // Columnas para la tabla
        $columnas = ['ID', 'Nombre', 'Correo', 'Verificado', 'Rol', 'Creado', 'Actualizado', 'Acciones'];

        $total_registros = $usuarios->count();

        $titulo = "CRUD Usuarios";

        // Obtener todos los roles disponibles para el dropdown en el modal de edición
        $roles = Role::pluck('name')->all();

        return view('Admin.cruds.usuarios', compact('usuarios', 'titulo', 'columnas', 'total_registros', 'roles'));
    }

    public function registro_existe($id)
    {
        $existe = DB::table('users')->where('id', '=', $id)->count();

        if ($existe > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function editar(Request $request)
    {
        $id = $request->input('id_editar'); // El ID original del usuario que se está editando
        $name = $request->input('name_mod');
        $newRole = $request->input('role_mod'); // El nuevo rol seleccionado
        $password = $request->input('password_mod');

        $user = User::findOrFail($id); // Encuentra al usuario por su ID

        // Actualiza el nombre del usuario
        $user->name = $name;

        // Actualiza la contraseña solo si se proporciona una nueva
        if (!empty($password)) {
            $user->password = Hash::make($password); // Hashea la nueva contraseña
        }

        $user->save(); // Guarda los cambios en el modelo de usuario

        // Sincroniza los roles del usuario. Esto eliminará los roles existentes
        // y asignará el nuevo rol. Asegúrate de que $newRole sea un string con el nombre del rol.
        $user->syncRoles($newRole);

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function eliminar(Request $request)
    {
        $id = $request->input('id_eliminar');

        DB::table('users')->where('id', '=', $id)->delete();
        return back();
    }

    public function eliminarMultiple(Request $request)
    {
        $ids_a_eliminar = [];
        // Recorre todos los IDs de usuario que existen para ver cuáles fueron seleccionados
        $existing_user_ids = DB::table('users')->pluck('id')->toArray();

        foreach ($existing_user_ids as $uid) {
            if ($request->has('id_eliminar_' . $uid)) {
                $ids_a_eliminar[] = $uid;
            }
        }

        if (!empty($ids_a_eliminar)) {
            DB::table('users')->whereIn('id', $ids_a_eliminar)->delete();
            return back()->with('success', 'Registros eliminados correctamente.');
        }

        return back()->with('error', 'No se seleccionaron registros para eliminar.');
    }
}
