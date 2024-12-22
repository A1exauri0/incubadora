<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MentorController extends Controller
{
    public function index()
    {
        $mentores = DB::table('mentor')->orderBy('fecha_agregado', 'desc')->get();
        $columnas = ['ID mentor', 'Nombre', 'Fecha Agregado'];
        $columnas = ['ID mentor', 'Nombre', 'Fecha Agregado'];
        $total_registros = $mentores->count();
    
        $titulo = "CRUD mentores";
    
        return view('Admin.cruds.mentores', compact('mentores', 'titulo', 'columnas', 'total_registros'));
    }
    

    public function registro_existe($clave)
    {
        $existe = DB::table('carrera')->where('clave', '=', $clave)->count();

        if ($existe > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function agregar(Request $request)
    {
        $nombre = $request->input('nombre_agregar');

        DB::table('mentor')->insert(['nombre' => $nombre,]);

        return back();
    }

    public function editar(Request $request)
    {
        $idMentor = $request->input('idMentor_editar');
        $nombre = $request->input('nombre_mod');

        DB::table('mentor')->where('idMentor', '=', $idMentor)->update(['nombre' => $nombre]);
        DB::table('mentor')->where('idMentor', '=', $idMentor)->update(['nombre' => $nombre]);
        return back();
    }

    public function eliminar(Request $request)
    {
        $clave = $request->input('idMentor_eliminar');

        DB::table('mentor')->where('idMentor', '=', $clave)->delete();
        return back();
    }

    public function eliminarMultiple(Request $request)
    {
        // realiza la eliiminacion de los registros seleccionados
        $nos_control = DB::table('mentor')->select('idMentor')->get();


        foreach ($nos_control as $nc) {
            try {
                $existe = $request->input('idMentor_eliminar_' . $nc->idMentor);
                if ($existe != null) {
                    DB::table('mentor')->where('idMentor', '=', $nc->idMentor)->delete();
                }
            } catch (\Exception $e) {
                $existe = null;
            }
        }

        return back();
    }
}