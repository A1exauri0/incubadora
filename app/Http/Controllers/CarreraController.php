<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarreraController extends Controller
{
    public function index()
    {
        $carreras = DB::table('carrera')->orderBy('nombre')->get();
        $columnas = ['Clave', 'Nombre','Fecha Agregado'];
        $total_registros = $carreras->count();

        //dd($carreras);

        $titulo = "CRUD Carreras";

        return view('Admin.cruds.carrera', compact('carreras', 'titulo', 'columnas', 'total_registros'));
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
        $clave = $request->input('clave_agregar');
        $nombre = $request->input('nombre_agregar');
        $carrera = $request->input('carrera_agregar');

        if ($this->registro_existe($clave)) {
            return back()->with('error', 'La clave que intentó agregar ya existe en otro registro.');
        } else {

            DB::table('carrera')->insert(['clave' => $clave, 'nombre' => $nombre]);
        }
        return back();
    }

    public function editar(Request $request)
    {
        $clave = $request->input('clave_editar');
        $clave_mod = $request->input('clave_mod');
        $nombre = $request->input('nombre_mod');

        if (($this->registro_existe($clave_mod)) && ($clave != $clave_mod)) {
            if ($this->registro_existe($clave)) {
                return back()->with('error', 'La clave que intentó modificar ya existe en otro registro.');
            }
        } else {
            DB::table('carrera')->where('clave', '=', $clave)->update(['clave' => $clave_mod, 'nombre' => $nombre]);
            return back();
        }
    }

    public function eliminar(Request $request)
    {
        $clave = $request->input('clave_eliminar');

        DB::table('carrera')->where('clave', '=', $clave)->delete();
        return back();
    }

    public function eliminarMultiple(Request $request)
    {
        // realiza la eliiminacion de los registros seleccionados
        $nos_control = DB::table('carrera')->select('clave')->get();


        foreach ($nos_control as $nc) {
            try {
                $existe = $request->input('clave_eliminar_' . $nc->clave);
                if ($existe != null) {
                    DB::table('carrera')->where('clave', '=', $nc->clave)->delete();
                }
            } catch (\Exception $e) {
                $existe = null;
            }
        }

        return back();
    }
}