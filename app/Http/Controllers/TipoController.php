<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoController extends Controller
{
    public function index()
    {
        $tipos = DB::table('tipo')->get();
        $columnas = ['ID', 'Nombre'];
        $total_registros = $tipos->count();

        //dd($alumnos);

        $titulo = "CRUD Tipos";

        return view('Admin.cruds.tipos', compact('tipos', 'titulo', 'columnas', 'total_registros'));
    }

    public function registro_existe($nombre)
    {
        $existe = DB::table('tipo')->where('nombre', '=', $nombre)->count();

        if ($existe > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function agregar(Request $request)
    {
        $nombre = $request->input('nombre_agregar');

        if ($this->registro_existe($nombre)) {
            return back()->with('error', 'El nombre que intentó agregar ya existe en otro registro.');
        } else {

            DB::table('tipo')->insert(['nombre' => $nombre]);
        }
        return back();
    }

    public function editar(Request $request)
    {
        $idTipo = $request->input('idTipo_editar');
        $nombre = $request->input('nombre_mod');

        DB::table('tipo')->where('idTipo', '=', $idTipo)->update(['nombre' => $nombre]);
        return back();
    }

    public function eliminar(Request $request)
    {
        $idTipo = $request->input('idTipo_eliminar');

        DB::table('tipo')->where('idTipo', '=', $idTipo)->delete();
        return back();
    }

    public function eliminarMultiple(Request $request)
    {
        // realiza la eliiminacion de los registros seleccionados
        $nos_control = DB::table('tipo')->select('idTipo')->get();


        foreach ($nos_control as $nc) {
            try {
                $existe = $request->input('idTipo_eliminar_' . $nc->idTipo);
                if ($existe != null) {
                    DB::table('tipo')->where('idTipo', '=', $nc->idTipo)->delete();
                }
            } catch (\Exception $e) {
                $existe = null;
            }
        }

        return back();
    }
}