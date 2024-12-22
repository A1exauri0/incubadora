<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = DB::table('categoria')->get();
        $columnas = ['ID', 'Nombre'];
        $total_registros = $categorias->count();

        //dd($alumnos);

        $titulo = "CRUD Categorias";

        return view('Admin.cruds.categorias', compact('categorias', 'titulo', 'columnas', 'total_registros'));
    }

    public function registro_existe($nombre)
    {
        $existe = DB::table('categoria')->where('nombre', '=', $nombre)->count();

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

            DB::table('categoria')->insert(['nombre' => $nombre]);
        }
        return back();
    }

    public function editar(Request $request)
    {
        $idCategoria = $request->input('idCategoria_editar');
        $nombre = $request->input('nombre_mod');

        DB::table('categoria')->where('idCategoria', '=', $idCategoria)->update(['nombre' => $nombre]);
        return back();
    }

    public function eliminar(Request $request)
    {
        $idCategoria = $request->input('idCategoria_eliminar');

        DB::table('categoria')->where('idCategoria', '=', $idCategoria)->delete();
        return back();
    }

    public function eliminarMultiple(Request $request)
    {
        // realiza la eliiminacion de los registros seleccionados
        $nos_control = DB::table('categoria')->select('idCategoria')->get();


        foreach ($nos_control as $nc) {
            try {
                $existe = $request->input('idCategoria_eliminar_' . $nc->idCategoria);
                if ($existe != null) {
                    DB::table('categoria')->where('idCategoria', '=', $nc->idCategoria)->delete();
                }
            } catch (\Exception $e) {
                $existe = null;
            }
        }

        return back();
    }
}