<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = DB::table('users')->orderByDesc('created_at')->get(['id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at']);
        $columnas = ['ID', 'Nombre','Correo','Verificado','Creado','Actualizado'];
        $total_registros = $usuarios->count();

        //dd($usuarios);

        $titulo = "CRUD usuarios";

        return view('Admin.cruds.usuarios', compact('usuarios', 'titulo', 'columnas', 'total_registros'));
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

    // public function agregar(Request $request)
    // {
    //     $id = $request->input('id_agregar');
    //     $nombre = $request->input('nombre_agregar');
    //     $usuario = $request->input('usuario_agregar');

    //     if ($this->registro_existe($id)) {
    //         return back()->with('error', 'La id que intentó agregar ya existe en otro registro.');
    //     } else {

    //         DB::table('usuario')->insert(['id' => $id, 'nombre' => $nombre]);
    //     }
    //     return back();
    // }

    public function editar(Request $request)
    {
        $id = $request->input('id_editar');
        $name = $request->input('name_mod');
        $password = $request->input('password_mod');

        $data = ['name' => $name];

        if (!empty($password)) {
            $data['password'] = bcrypt($password);
        }

        DB::table('users')->where('id', $id)->update($data);

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
        // realiza la eliiminacion de los registros seleccionados
        $nos_control = DB::table('users')->select('id')->get();


        foreach ($nos_control as $nc) {
            try {
                $existe = $request->input('id_eliminar_' . $nc->id);
                if ($existe != null) {
                    DB::table('users')->where('id', '=', $nc->id)->delete();
                }
            } catch (\Exception $e) {
                $existe = null;
            }
        }

        return back();
    }
}