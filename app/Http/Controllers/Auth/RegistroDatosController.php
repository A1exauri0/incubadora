<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistroDatosController extends Controller
{
    public function index()
    {
        return view('auth.registro-datos');
    }
    // Muestra el formulario de registro de datos después de la verificación del email
    public function showForm()
    {
        $correo = Auth::user()->email;
        $alumno = DB::table('alumno')->where('correo_institucional', $correo)->first();
        
        return view('auth.registro-datos', compact('alumno'));
    }

    // Guarda o actualiza los datos del alumno en la base de datos
    public function store(Request $request)
    {
        $correo = Auth::user()->email;

        $alumno = DB::table('alumno')->where('correo_institucional', $correo)->first();

        if ($alumno) {
            DB::table('alumno')->where('correo_institucional', $correo)->update([
                'nombre' => $request->nombre,
                'carrera' => $request->carrera,
                'telefono' => $request->telefono,
                'semestre' => $request->semestre
            ]);
        } else {
            DB::table('alumno')->insert([
                'no_control' => $request->no_control,
                'nombre' => $request->nombre,
                'carrera' => $request->carrera,
                'correo_institucional' => $correo,
                'telefono' => $request->telefono,
                'semestre' => $request->semestre,
                'fecha_agregado' => now()
            ]);
        }

        return redirect()->route('home')->with('success', 'Registro completado.');
    }
}
