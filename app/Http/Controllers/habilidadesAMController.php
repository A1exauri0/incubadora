<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class habilidadesAMController extends Controller
{
    public function index()
    {
        $titulo="CRUD de Habilidades de Asesores y/o Mentores";
        return view('Admin/cruds/habilidadesam',compact('titulo')) ;
    }

    public function agregar()
    {
        return view('habilidadesAM.crear');
    }

    public function editar()
    {
        return view('habilidadesAM.editar');
    }

    public function eliminar()
    {
        return view('habilidadesAM.eliminar');
    }

    public function eliminar_multiple()
    {
        return view('habilidadesAM.eliminarMultiple');
    }
    
}
