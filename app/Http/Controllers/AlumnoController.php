<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumnoController extends Controller
{
    public function index()
    {
        $alumnos = DB::table('alumno')->orderBy('no_control')->get();
        $carreras = DB::table('carrera')->get();
        $columnas = ['No. Control', 'Nombre', 'Carrera', 'Correo institucional','Fecha Agregado'];
        $total_registros = $alumnos->count();

        //dd($alumnos);

        $titulo = "CRUD Alumnos";

        return view('Admin.cruds.alumnos', compact('alumnos', 'titulo', 'carreras', 'columnas', 'total_registros'));
    }

    public function registro_existe($no_control)
    {
        $existe = DB::table('alumno')->where('no_control', '=', $no_control)->count();

        if ($existe > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function agregar(Request $request)
    {
        $no_control = $request->input('no_control_agregar');
        $nombre = $request->input('nombre_agregar');
        $carrera = $request->input('carrera_agregar');
        $correo = $request->input('correo_agregar');

        if ($this->registro_existe($no_control)) {
            return back()->with('error', 'El numero de control que intentó agregar ya existe en otro registro.');
        } else {

            DB::table('alumno')->insert(['no_control' => $no_control, 'nombre' => $nombre, 'carrera' => $carrera, 'correo_institucional' => $correo]);
        }
        return back();
    }

    public function editar(Request $request)
    {
        $no_control = $request->input('no_control_editar');
        $no_control_mod = $request->input('no_control_mod');
        $nombre = $request->input('nombre_mod');
        $carrera = $request->input('carrera_mod');
        $correo_institucional = $request->input('correo_institucional_mod');

        if (($this->registro_existe($no_control_mod)) && ($no_control != $no_control_mod)) {
            if ($this->registro_existe($no_control)) {
                return back()->with('error', 'El numero de control que intentó modificar ya existe en otro registro.');
            }
        } else {
            DB::table('alumno')->where('no_control', '=', $no_control)->update(['no_control' => $no_control_mod, 'nombre' => $nombre, 'carrera' => $carrera, 'correo_institucional' => $correo_institucional]);
            return back();
        }
    }

    public function eliminar(Request $request)
    {
        $no_control = $request->input('no_control_eliminar');

        DB::table('alumno')->where('no_control', '=', $no_control)->delete();
        return back();
    }

    public function eliminarMultiple(Request $request)
    {
        // realiza la eliiminacion de los registros seleccionados
        $nos_control = DB::table('alumno')->select('no_control')->get();


        foreach ($nos_control as $nc) {
            try {
                $existe = $request->input('no_control_eliminar_' . $nc->no_control);
                if ($existe != null) {
                    DB::table('alumno')->where('no_control', '=', $nc->no_control)->delete();
                }
            } catch (\Exception $e) {
                $existe = null;
            }
        }

        return back();
    }
}