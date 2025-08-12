<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AsesorController extends Controller
{
    public function index()
    {
        $asesores = DB::table('asesor')->orderBy('fecha_agregado', 'desc')->get();
        $columnas = ['ID Asesor', 'Nombre', 'Teléfono', 'Correo Electrónico', "Fecha Agregado"];
        $total_registros = $asesores->count();

        $titulo = "CRUD Asesores";

        return view('Admin.cruds.asesores', compact('asesores', 'titulo', 'columnas', 'total_registros'));
    }

public function showHabilidades()
    {
        $user = Auth::user();

        if (!$user) {
            return back()->with('error', 'Usuario no autenticado.');
        }

        $asesor = DB::table('asesor')->where('correo_electronico', $user->email)->first();

        if (!$asesor) {
            return back()->with('error', 'No se pudo encontrar el perfil de asesor.');
        }

        // Obtener todas las habilidades del catálogo
        $habilidadesCatalogo = DB::table('habilidad')->get();
        
        // Obtener las habilidades que el asesor ya tiene
        $misHabilidades = DB::table('habilidad_asesor')
            ->join('habilidad', 'habilidad_asesor.idHabilidad', '=', 'habilidad.idHabilidad')
            ->where('habilidad_asesor.idAsesor', $asesor->idAsesor)
            ->get();
            
        // Obtener los IDs de las habilidades del asesor
        $habilidadesAsesorIds = $misHabilidades->pluck('idHabilidad')->toArray();

        // Filtrar las habilidades del catálogo para obtener solo las disponibles
        $habilidadesDisponibles = $habilidadesCatalogo->whereNotIn('idHabilidad', $habilidadesAsesorIds);

        return view('asesores.mis_habilidades', compact('misHabilidades', 'habilidadesDisponibles'));
    }
    
    // Método para agregar una habilidad del catálogo al asesor
    public function addCatalogHabilidad(Request $request)
    {
        $request->validate([
            'idHabilidad' => 'required|exists:habilidad,idHabilidad',
        ]);

        $user = Auth::user();
        $asesor = DB::table('asesor')->where('correo_electronico', $user->email)->first();

        if (!$asesor) {
            return back()->with('error', 'No se pudo encontrar el perfil de asesor.');
        }

        $asesorId = $asesor->idAsesor;
        $habilidadId = $request->input('idHabilidad');

        DB::beginTransaction();
        try {
            // Verificar si la habilidad ya está asignada para evitar duplicados
            $existe = DB::table('habilidad_asesor')
                ->where('idAsesor', $asesorId)
                ->where('idHabilidad', $habilidadId)
                ->exists();

            if ($existe) {
                return back()->with('error', 'Esta habilidad ya ha sido asignada.');
            }

            // Asignar la habilidad al asesor
            DB::table('habilidad_asesor')->insert([
                'idAsesor' => $asesorId,
                'idHabilidad' => $habilidadId,
            ]);
            
            DB::commit();
            return back()->with('success', 'Habilidad añadida correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al guardar la habilidad: ' . $e->getMessage());
        }
    }
    
    public function storeHabilidades(Request $request)
    {
        $user = Auth::user();
        $asesor = DB::table('asesor')->where('correo_electronico', $user->email)->first();

        if (!$asesor) {
            return back()->with('error', 'No se pudo encontrar el perfil de asesor.');
        }

        $asesorId = $asesor->idAsesor;
        $habilidadesSeleccionadas = $request->input('habilidades', []);

        DB::beginTransaction();
        try {
            DB::table('habilidad_asesor')->where('idAsesor', $asesorId)->delete();

            $habilidadesInsert = [];
            foreach ($habilidadesSeleccionadas as $habilidadId) {
                $habilidadesInsert[] = [
                    'idAsesor' => $asesorId,
                    'idHabilidad' => $habilidadId,
                ];
            }
            
            if (!empty($habilidadesInsert)) {
                 DB::table('habilidad_asesor')->insert($habilidadesInsert);
            }
            
            DB::commit();
            return back()->with('success', 'Habilidades actualizadas correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al guardar las habilidades: ' . $e->getMessage());
        }
    }
    
    public function addCustomHabilidad(Request $request)
    {
        $request->validate([
            'nombre_habilidad' => 'required|string|max:255',
            'descripcion_habilidad' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $asesor = DB::table('asesor')->where('correo_electronico', $user->email)->first();

        if (!$asesor) {
            return back()->with('error', 'No se pudo encontrar el perfil de asesor.');
        }

        DB::beginTransaction();
        try {
            // Inserta la nueva habilidad en la tabla `habilidad`
            $habilidadId = DB::table('habilidad')->insertGetId([
                'nombre' => $request->nombre_habilidad,
                'descripcion' => $request->descripcion_habilidad,
            ]);

            // Asigna la nueva habilidad al asesor
            DB::table('habilidad_asesor')->insert([
                'idAsesor' => $asesor->idAsesor,
                'idHabilidad' => $habilidadId,
            ]);

            DB::commit();
            return back()->with('success', 'Habilidad personalizada añadida con éxito y asignada.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al añadir la habilidad personalizada: ' . $e->getMessage());
        }
    }
    
    public function destroyHabilidad($id)
    {
        $user = Auth::user();
        $asesor = DB::table('asesor')->where('correo_electronico', $user->email)->first();

        if (!$asesor) {
            return back()->with('error', 'No se pudo encontrar el perfil de asesor.');
        }

        DB::beginTransaction();
        try {
            // Eliminar la relación de la tabla `habilidad_asesor`
            DB::table('habilidad_asesor')
                ->where('idAsesor', $asesor->idAsesor)
                ->where('idHabilidad', $id)
                ->delete();

            // Opcionalmente, puedes eliminar la habilidad si es personalizada y solo la tiene este asesor
            // Esto es una decisión de negocio. Por ahora, solo eliminamos la relación.
            
            DB::commit();
            return back()->with('success', 'Habilidad eliminada correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al eliminar la habilidad: ' . $e->getMessage());
        }
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