<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EtapaController extends Controller
{
    public function index(){
        $etapas =  DB::table('etapas')->get();
        $colores = DB::table('color')->get();
        $columnas = ['ID', 'Nombre','Descripción','Color'];
        $total_registros = $etapas->count();
        $titulo = "CRUD Etapas";

        return view ('Admin.cruds.etapas', compact('titulo','etapas','colores','columnas','total_registros'));
    }

    public function registro_existe($nombre)
    {
        $existe = DB::table('etapas')->where('nombre', '=', $nombre)->count();

        if ($existe > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function agregar(Request $request){
        $nombre = $request->input('nombre_agregar');
        $descripcion = $request->input('descripcion_agregar');
        $color = $request->input('color_agregar');

        if($this->registro_existe($nombre)){
            return back()->with('error', 'El nombre que intentó agregar ya existe en otro registro.');
        }else{

        DB::table('etapas')->insert(['nombre' => $nombre,'descripcion'=>$descripcion, 'color' => $color]);

        }
        return back();
    }

    public function editar(Request $request)
    {
        $idEtapa = $request->input('idEtapa_editar');
        $nombre = $request->input('nombre_mod');
        $descripcion = $request->input('descripcion_mod');
        $color = $request->input('color_mod');

        DB::table('etapas')->where('idEtapa', '=', $idEtapa)->update(['nombre' => $nombre,'descripcion'=>$descripcion, 'color' => $color]);
        return back();
    }

    public function eliminar(Request $request)
    {
        $idEtapa = $request->input('idEtapa_eliminar');

        DB::table('etapas')->where('idEtapa', '=', $idEtapa)->delete();
        return back();
    }

    public function eliminarMultiple(Request $request)
    {
        // realiza la eliiminacion de los registros seleccionados
        $nos_control = DB::table('etapas')->select('idEtapa')->get();


        foreach ($nos_control as $nc) {
            try {
                $existe = $request->input('idEtapa_eliminar_' . $nc->idEtapa);
                if ($existe != null) {
                    DB::table('etapas')->where('idEtapa', '=', $nc->idEtapa)->delete();
                }
            } catch (\Exception $e) {
                $existe = null;
            }
        }

        return back();
    }
}