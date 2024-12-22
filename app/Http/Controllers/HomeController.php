<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Obtener todos los proyectos, categorías, tipos, etapas y colores
        $proyectos = DB::table('proyecto')->get();
        $categorias = DB::table('categoria')->get();
        $tipos = DB::table('tipo')->get();
        $etapas = DB::table('etapas')->get();
        $colores = DB::table('color')->get();

        // Se asigna la clase correspondiente a cada etapa para la simbología
        foreach ($etapas as $etapa) {
            // Encuentra el color correspondiente en base al nombre del color
            if ($color = $colores->firstWhere('nombre', $etapa->color)) {
                $etapa->clase = $color->clase;
            } 
        }

        // Asignar la clase a cada proyecto
        foreach ($proyectos as $proyecto) {
            // Encontrar la etapa correspondiente al proyecto
            $etapa = $etapas->firstWhere('idEtapa', $proyecto->etapa);

            // Verificar si la etapa fue encontrada y tiene clase
            if ($etapa && isset($etapa->clase)) {
                // Asignar la clase de la etapa al proyecto
                $proyecto->clase = $etapa->clase;
            } else {
                // Asignar una clase por defecto si no hay etapa válida
                $proyecto->clase = 'default-class'; 
            }
        }

        return view('home', [
            'titulo' => 'Inicio',
            'proyectos' => $proyectos,
            'categorias' => $categorias,
            'tipos' => $tipos,
            'etapas' => $etapas,
        ]);
    }
}