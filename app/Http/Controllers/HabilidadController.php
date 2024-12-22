<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HabilidadController extends Controller
{
    public function index()
    {
        $habilidades = DB::table('habilidad')->get();
        $columnas = ['ID', 'Nombre'];
        $total_registros = $habilidades->count();

        //dd($alumnos);

        $titulo = "CRUD Habilidades";

        return view('Admin.cruds.habilidades', compact('habilidades', 'titulo', 'columnas', 'total_registros'));
    }

    public function registro_existe($nombre)
    {
        $existe = DB::table('habilidad')->where('nombre', '=', $nombre)->count();

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

            DB::table('habilidad')->insert(['nombre' => $nombre]);
        }
        return back();
    }

    public function editar(Request $request)
    {
        $idHabilidad = $request->input('idHabilidad_editar');
        $nombre = $request->input('nombre_mod');

        DB::table('habilidad')->where('idHabilidad', '=', $idHabilidad)->update(['nombre' => $nombre]);
        return back();
    }

    public function eliminar(Request $request)
    {
        $idHabilidad = $request->input('idHabilidad_eliminar');

        DB::table('habilidad')->where('idHabilidad', '=', $idHabilidad)->delete();
        return back();
    }

    public function eliminarMultiple(Request $request)
    {
        // realiza la eliiminacion de los registros seleccionados
        $nos_control = DB::table('habilidad')->select('idHabilidad')->get();


        foreach ($nos_control as $nc) {
            try {
                $existe = $request->input('idHabilidad_eliminar_' . $nc->idHabilidad);
                if ($existe != null) {
                    DB::table('habilidad')->where('idHabilidad', '=', $nc->idHabilidad)->delete();
                }
            } catch (\Exception $e) {
                $existe = null;
            }
        }

        return back();
    }
}