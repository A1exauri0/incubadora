<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
{
    public function index()
    {
        $proyectos = DB::table('proyecto')->orderBy('fecha_agregado', 'desc')->orderBy('fecha_agregado','desc')->get();
        $columnas = ['Clave', 'Nombre', 'Desc.','Categoria', 'Tipo','Etapa','Video','Registrado'];
        $categorias = DB::table('categoria')->get();
        $alumnos = DB::table('alumno')->get();
        $tipos = DB::table('tipo')->get();
        $etapas = DB::table('etapas')->get();
        $total_registros = $proyectos->count();

        //dd($proyectos);

        $titulo = "CRUD Proyectos";

        return view('Admin.cruds.proyectos', compact('proyectos', 'titulo', 'columnas', 'total_registros','categorias','alumnos','tipos','etapas'));
    }

    public function registro_existe($clave_proyecto)
    {
        $existe = DB::table('proyecto')->where('clave_proyecto', '=', $clave_proyecto)->count();

        if ($existe > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function agregar(Request $request)
    {
        $clave_proyecto = $request->input('clave_proyecto_agregar');
        $nombre = $request->input('nombre_agregar');
        $descripcion = $request->input('descripcion_agregar');
        $categoria= $request->input('categoria_agregar');
        $tipo= $request->input('tipo_agregar');
        $etapa = $request->input('etapa_agregar');
        $video = $request->input('video_agregar');

        //bloque de depuracion
        //dd($clave_proyecto, $nombre, $descripcion, $categoria, $tipo);
        //FIN bloque de depuracion

        if ($this->registro_existe($clave_proyecto)) {
            return back()->with('error', 'La clave del proyecto que intentó agregar ya existe en otro registro.');
        } else {
            DB::table('proyecto')->insert(['clave_proyecto' => $clave_proyecto, 'nombre' => $nombre, 'descripcion' => $descripcion, 'categoria' => $categoria, 'tipo' => $tipo,'etapa' => $etapa, 'video' => $video]);
        }
        return back();
    }

    public function editar(Request $request)
    {
        $clave_proyecto = $request->input('clave_proyecto_editar');
        $clave_proyecto_mod = $request->input('clave_proyecto_mod');
        $nombre = $request->input('nombre_mod');
        $descripcion = $request->input('descripcion_mod');
        $categoria= $request->input('categoria_mod');
        $tipo= $request->input('tipo_mod');
        $etapa=$request->input('etapa_mod');
        $video = $request->input('video_mod');

        if (($this->registro_existe($clave_proyecto_mod)) && ($clave_proyecto != $clave_proyecto_mod)) {
            if ($this->registro_existe($clave_proyecto)) {
                return back()->with('error', 'La clave del proyecto que intentó modificar ya existe en otro registro.');
            }
        } else {
            DB::table('proyecto')->where('clave_proyecto', '=', $clave_proyecto)->update(['clave_proyecto' => $clave_proyecto_mod, 'nombre' => $nombre,
            'descripcion' => $descripcion, 'categoria' => $categoria, 'tipo' => $tipo,'etapa' =>$etapa,'video' => $video]);
            return back();
        }
    }

    public function eliminar(Request $request)
    {
        $clave_proyecto = $request->input('clave_proyecto_eliminar');

        DB::table('proyecto')->where('clave_proyecto', '=', $clave_proyecto)->delete();
        return back();
    }

    public function eliminarMultiple(Request $request)
    {
        // realiza la eliiminacion de los registros seleccionados
        $nos_control = DB::table('proyecto')->select('clave_proyecto')->get();


        foreach ($nos_control as $nc) {

                $existe = $request->input('clave_proyecto_eliminar_' . $nc->clave_proyecto);
                if ($existe != null) {
                    DB::table('proyecto')->where('clave_proyecto', '=', $nc->clave_proyecto)->delete();
                }

                $existe = null;
            
        }

        return back();
    }
}