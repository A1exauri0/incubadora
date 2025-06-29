<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\TokenCreatedMail;
use Illuminate\Support\Facades\URL;

class TokenController extends Controller
{
    // Asegúrate de que esta línea esté presente si quieres proteger todas las rutas del controlador
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tokens = DB::table('token_rol')->orderBy('idToken', 'desc')->get();
        // Asegurarse de que el nombre del rol se obtiene para cada token para la vista
        foreach ($tokens as $token_item) {
            $role_name = DB::table('roles')->where('id', $token_item->rol)->first();
            $token_item->rol_nombre = $role_name ? $role_name->name : 'Desconocido';
        }
        $columnas = ['ID', 'Token', 'Rol', 'Correo'];
        $total_registros = $tokens->count();
        $roles = DB::table('roles')->get();

        $titulo = "CRUD Tokens";

        return view('Admin.cruds.tokens', compact('tokens', 'roles', 'titulo', 'columnas', 'total_registros'));
    }

    public function registro_existe($correo)
    {
        $existe = DB::table('token_rol')->where('correo', '=', $correo)->count();
        return $existe > 0;
    }

    public function agregar(Request $request)
    {
        $token = uniqid();
        $correo = $request->input('correo_agregar');
        $rolName = $request->input('rol_agregar');

        $rolObject = DB::table('roles')->where('name', '=', $rolName)->first();

        if (!$rolObject) {
            return back()->with('error', 'El rol seleccionado no es válido.');
        }

        $rolId = $rolObject->id;

        if ($this->registro_existe($correo)) {
            return back()->with('error', 'El correo ya tiene un código asignado. Si quiere cambiarlo, elimine el existente primero.');
        } else {
            DB::table('token_rol')->insert([
                'token' => $token,
                'correo' => $correo,
                'rol' => $rolId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            try {
                // CAMBIO CLAVE AQUÍ: Añadir 'email' a los parámetros de la URL
                $registrationUrl = route('register', ['token' => $token, 'email' => $correo]);

                Mail::to($correo)->send(new TokenCreatedMail(
                    $token,
                    $correo,
                    $rolName,
                    $registrationUrl,
                    'Completar Registro'
                ));

                return back()->with('success', 'Correo enviado.');
            } catch (\Exception $e) {
                return back()->with('error', 'Token generado, pero falló el envío del correo: ' . $e->getMessage());
            }
        }
    }

    public function editar(Request $request)
    {
        $idtoken = $request->input('idToken_editar');
        $correo = $request->input('correo_mod');
        $rolName = $request->input('rol_mod');

        $rolObject = DB::table('roles')->where('name', '=', $rolName)->first();

        if (!$rolObject) {
            return back()->with('error', 'El rol seleccionado no es válido para la edición.');
        }
        $rolId = $rolObject->id;

        $existingTokenForCorreo = DB::table('token_rol')
                                    ->where('correo', $correo)
                                    ->where('idToken', '!=', $idtoken)
                                    ->first();

        if ($existingTokenForCorreo) {
            return back()->with('error', 'El correo ya está asignado a otro token.');
        }

        DB::table('token_rol')->where('idToken', '=', $idtoken)->update([
            'correo' => $correo,
            'rol' => $rolId,
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Token actualizado exitosamente.');
    }

    public function eliminar(Request $request)
    {
        $idtoken = $request->input('idToken_eliminar');
        DB::table('token_rol')->where('idToken', '=', $idtoken)->delete();
        return back()->with('success', 'Token eliminado exitosamente.');
    }

    public function eliminarMultiple(Request $request)
    {
        $idTokensToDelete = $request->input('options', []);

        if (empty($idTokensToDelete)) {
            return back()->with('error', 'No se seleccionaron tokens para eliminar.');
        }

        DB::table('token_rol')->whereIn('idToken', $idTokensToDelete)->delete();

        return back()->with('success', 'Tokens seleccionados eliminados exitosamente.');
    }
}
