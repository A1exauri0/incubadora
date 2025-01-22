<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TokenController extends Controller
{
    public function index()
    {
        $tokens = DB::table('token_rol')->get();
        $columnas = ['ID', 'Token', 'Rol','Correo'];
        $total_registros = $tokens->count();
        $roles = DB::table('roles')->get();

        //dd($alumnos);

        $titulo = "CRUD Tokens";

        return view('Admin.cruds.tokens', compact('tokens','roles', 'titulo', 'columnas', 'total_registros'));
    }

    public function registro_existe($correo)
    {
        $existe = DB::table('token_rol')->where('correo', '=', $correo)->count();

        if ($existe > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function agregar(Request $request)
    {
        $token = uniqid();
        $correo = $request->input('correo_agregar');
        $rol = $request->input('rol_agregar');

        //convertir el rol a su id
        $rol = DB::table('roles')->where('name', '=', $rol)->first()->id;

        if ($this->registro_existe($correo)) {
            return back()->with('error', 'El correo ya tiene un código asignado, si quiere cambiarlo elimine el existente primero.');
        } else {
            DB::table('token_rol')->insert(['token' => $token, 'correo' => $correo, 'rol' => $rol]);
        }
        return back();
    }

    public function editar(Request $request)
    {
        $idtoken = $request->input('idToken_editar');
        $nombre = $request->input('correo_mod');
        $rol = $request->input('rol_mod');

        DB::table('token_rol')->where('idToken', '=', $idtoken)->update(['correo' => $correo, 'rol' => $rol]);
        return back();
    }

    public function eliminar(Request $request)
    {
        $idtoken = $request->input('idToken_eliminar');

        DB::table('token_rol')->where('idToken', '=', $idtoken)->delete();

        return back();
    }

    public function eliminarMultiple(Request $request)
    {
        // realiza la eliiminacion de los registros seleccionados
        $nos_control = DB::table('token_rol')->select('idToken')->get();


        foreach ($nos_control as $nc) {
            try {
                $existe = $request->input('idToken_eliminar_' . $nc->idToken);
                if ($existe != null) {
                    DB::table('token_rol')->where('idToken', '=', $nc->idToken)->delete();
                }
            } catch (\Exception $e) {
                $existe = null;
            }
        }

        return back();
    }
}