<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsesorController extends Controller
{
    public function index()
    {
        $asesores = DB::table('asesor')->orderBy('fecha_agregado', 'desc')->get();
        $columnas = ['ID Asesor', 'Nombre','Teléfono','Correo Electrónico', "Fecha Agregado"];
        $total_registros = $asesores->count();

        $titulo = "CRUD Asesores";

        return view('Admin.cruds.asesores', compact('asesores', 'titulo', 'columnas', 'total_registros'));
    }

    public function registro_existe($nombre)
    {
        $existe = DB::table('asesor')->where('nombre', '=', $nombre)->count();

        if ($existe > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function agregar(Request $request)
    {
        $nombre = $request->input('nombre_agregar');
        $telefono = $request->input('telefono_agregar');
        $correo = $request->input('correo_agregar');

        if ($this->registro_existe(($nombre))) {
            return back()->with('error', 'El asesor que intentó agregar ya existe en otro registro.');
        } else {
            DB::table('asesor')->insert(['nombre' => $nombre, 'telefono' => $telefono,'correo_electronico' => $correo]);
        }

        return back();
    }

    public function editar(Request $request)
    {
        $idAsesor = $request->input('idAsesor_editar');
        $nombre = $request->input('nombre_mod');
        $telefono = $request->input('telefono_mod');
        $correo = $request->input('correo_mod');

        DB::table('asesor')->where('idAsesor', '=', $idAsesor)->update(['nombre' => $nombre, 'telefono' => $telefono,'correo_electronico' => $correo]);
        return back();
    }

    public function eliminar(Request $request)
    {
        $clave = $request->input('idAsesor_eliminar');

        DB::table('asesor')->where('idAsesor', '=', $clave)->delete();
        return back();
    }

    public function eliminarMultiple(Request $request)
    {
        // realiza la eliiminacion de los registros seleccionados
        $nos_control = DB::table('asesor')->select('idAsesor')->get();


        foreach ($nos_control as $nc) {
            try {
                $existe = $request->input('idAsesor_eliminar_' . $nc->idAsesor);
                if ($existe != null) {
                    DB::table('asesor')->where('idAsesor', '=', $nc->idAsesor)->delete();
                }
            } catch (\Exception $e) {
                $existe = null;
            }
        }

        return back();
    }
}