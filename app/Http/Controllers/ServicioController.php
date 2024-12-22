<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = DB::table('servicio')->get();
        $columnas = ['ID', 'Nombre', 'Descripcion'];
        $total_registros = $servicios->count();

        //dd($alumnos);

        $titulo = "CRUD Servicios";

        return view('Admin.cruds.servicios', compact('servicios', 'titulo', 'columnas', 'total_registros'));
    }

    public function registro_existe($nombre)
    {
        $existe = DB::table('servicio')->where('nombre', '=', $nombre)->count();

        if ($existe > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function agregar(Request $request)
    {
        $nombre = $request->input('nombre_agregar');
        $descripcion = $request->input('descripcion_agregar');

        if ($this->registro_existe($nombre)) {
            return back()->with('error', 'El nombre que intentó agregar ya existe en otro registro.');
        } else {

            DB::table('servicio')->insert(['nombre' => $nombre, 'descripcion' => $descripcion]);
        }
        return back();
    }

    public function editar(Request $request)
    {
        $idServicio = $request->input('idServicio_editar');
        $nombre = $request->input('nombre_mod');
        $descripcion = $request->input('descripcion_mod');

        DB::table('servicio')->where('idServicio', '=', $idServicio)->update(['nombre' => $nombre]);
        DB::table('servicio')->where('idServicio', '=', $idServicio)->update(['descripcion' => $descripcion]);
        return back();
    }

    public function eliminar(Request $request)
    {
        $idServicio = $request->input('idServicio_eliminar');

        DB::table('servicio')->where('idServicio', '=', $idServicio)->delete();
        return back();
    }

    public function eliminarMultiple(Request $request)
    {
        // realiza la eliiminacion de los registros seleccionados
        $nos_control = DB::table('servicio')->select('idServicio')->get();


        foreach ($nos_control as $nc) {
            try {
                $existe = $request->input('idServicio_eliminar_' . $nc->idServicio);
                if ($existe != null) {
                    DB::table('servicio')->where('idServicio', '=', $nc->idServicio)->delete();
                }
            } catch (\Exception $e) {
                $existe = null;
            }
        }

        return back();
    }
}