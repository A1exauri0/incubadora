<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
{
    public function index()
    {
        $proyectos = DB::table('proyecto')->orderBy('fecha_agregado', 'desc')->orderBy('fecha_agregado','desc')->get();
        // Las columnas en la vista principal NO CAMBIAN según tu requisito
        $columnas = ['Clave', 'Nombre', 'Nombre descriptivo','Categoria', 'Tipo','Etapa','Video','Registrado'];
        $categorias = DB::table('categoria')->get();
        $alumnos = DB::table('alumno')->get(); // ¿Este se usa? Si no, se puede quitar.
        $tipos = DB::table('tipo')->get();
        $etapas = DB::table('etapas')->get();
        $total_registros = $proyectos->count();

        //dd($proyectos);

        $titulo = "CRUD Proyectos";

        // Asegúrate de pasar todas las variables necesarias a la vista
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
        $nombre_descriptivo = $request->input('nombre_descriptivo_agregar');
        $descripcion = $request->input('descripcion_agregar');
        $categoria= $request->input('categoria_agregar');
        $tipo= $request->input('tipo_agregar');
        $etapa = $request->input('etapa_agregar');
        $video = $request->input('video_agregar');
        // NUEVOS CAMPOS
        $area_aplicacion = $request->input('area_aplicacion_agregar');
        $naturaleza_tecnica = $request->input('naturaleza_tecnica_agregar');
        $objetivo = $request->input('objetivo_agregar');


        //bloque de depuracion
        //dd($clave_proyecto, $nombre, $descripcion, $categoria, $tipo, $area_aplicacion, $naturaleza_tecnica, $objetivo);
        //FIN bloque de depuracion

        if ($this->registro_existe($clave_proyecto)) {
            return back()->with('error', 'La clave del proyecto que intentó agregar ya existe en otro registro.');
        } else {
            DB::table('proyecto')->insert([
                'clave_proyecto' => $clave_proyecto,
                'nombre' => $nombre,
                'nombre_descriptivo' => $nombre_descriptivo,
                'descripcion' => $descripcion,
                'categoria' => $categoria,
                'tipo' => $tipo,
                'etapa' => $etapa,
                'video' => $video,
                // NUEVOS CAMPOS
                'area_aplicacion' => $area_aplicacion,
                'naturaleza_tecnica' => $naturaleza_tecnica,
                'objetivo' => $objetivo
            ]);
        }
        return back();
    }

    public function editar(Request $request)
    {
        $clave_proyecto = $request->input('clave_proyecto_editar'); // Clave original del proyecto a editar
        $clave_proyecto_mod = $request->input('clave_proyecto_mod'); // Nueva clave (si se modificó)
        $nombre = $request->input('nombre_mod');
        $nombre_descriptivo = $request->input('nombre_descriptivo_mod');
        $descripcion = $request->input('descripcion_mod');
        $categoria= $request->input('categoria_mod');
        $tipo= $request->input('tipo_mod');
        $etapa=$request->input('etapa_mod');
        $video = $request->input('video_mod');
        // NUEVOS CAMPOS
        $area_aplicacion = $request->input('area_aplicacion_mod');
        $naturaleza_tecnica = $request->input('naturaleza_tecnica_mod');
        $objetivo = $request->input('objetivo_mod');


        // Verificar si la nueva clave ya existe y es diferente a la original
        if ($clave_proyecto != $clave_proyecto_mod && $this->registro_existe($clave_proyecto_mod)) {
            return back()->with('error', 'La clave del proyecto que intentó modificar ya existe en otro registro.');
        } else {
            DB::table('proyecto')->where('clave_proyecto', '=', $clave_proyecto)->update([
                'clave_proyecto' => $clave_proyecto_mod,
                'nombre' => $nombre,
                'nombre_descriptivo' => $nombre_descriptivo,
                'descripcion' => $descripcion,
                'categoria' => $categoria,
                'tipo' => $tipo,
                'etapa' => $etapa,
                'video' => $video,
                // NUEVOS CAMPOS
                'area_aplicacion' => $area_aplicacion,
                'naturaleza_tecnica' => $naturaleza_tecnica,
                'objetivo' => $objetivo
            ]);
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