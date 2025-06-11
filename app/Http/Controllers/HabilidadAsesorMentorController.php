<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asesor;
use App\Models\Mentor;
use App\Models\Habilidad;

class HabilidadAsesorMentorController extends Controller
{
    /**
     * Muestra la vista principal del CRUD de asignación de habilidades.
     */
    public function index()
    {
        $titulo = "Asignar Habilidades";
        $asesores = Asesor::all(['idAsesor', 'nombre']); // Solo id y nombre
        $mentores = Mentor::all(['idMentor', 'nombre']); // Solo id y nombre
        $habilidades = Habilidad::all(['idHabilidad', 'nombre']); // Solo id y nombre

        return view('Admin.cruds.habilidades_asignar', compact('titulo', 'asesores', 'mentores', 'habilidades'));
    }

    /**
     * Obtiene los usuarios (asesores o mentores) según el tipo seleccionado.
     */
    public function getUsuariosPorTipo(Request $request)
    {
        $tipo = $request->input('tipo');
        $usuarios = [];

        if ($tipo === 'asesor') {
            $usuarios = Asesor::all(['idAsesor as id', 'nombre'])->toArray();
        } elseif ($tipo === 'mentor') {
            $usuarios = Mentor::all(['idMentor as id', 'nombre'])->toArray();
        }

        return response()->json($usuarios);
    }

    /**
     * Obtiene las habilidades actuales del usuario seleccionado y las disponibles.
     */
    public function getHabilidadesUsuario(Request $request)
    {
        $tipo = $request->input('tipo');
        $idUsuario = $request->input('idUsuario');

        $currentSkills = [];
        $availableSkills = [];

        if ($tipo === 'asesor') {
            $asesor = Asesor::find($idUsuario);
            if ($asesor) {
                $currentSkills = $asesor->habilidades()->get(['habilidad.idHabilidad', 'habilidad.nombre'])->toArray();
            }
        } elseif ($tipo === 'mentor') {
            $mentor = Mentor::find($idUsuario);
            if ($mentor) {
                $currentSkills = $mentor->habilidades()->get(['habilidad.idHabilidad', 'habilidad.nombre'])->toArray();
            }
        }

        // Obtener todas las habilidades
        $allSkills = Habilidad::all(['idHabilidad', 'nombre'])->toArray();

        // Calcular habilidades disponibles (todas - actuales)
        $currentSkillIds = array_column($currentSkills, 'idHabilidad');
        foreach ($allSkills as $skill) {
            if (!in_array($skill['idHabilidad'], $currentSkillIds)) {
                $availableSkills[] = $skill;
            }
        }

        return response()->json([
            'currentSkills' => $currentSkills,
            'availableSkills' => $availableSkills
        ]);
    }

    /**
     * Asigna una habilidad a un asesor o mentor.
     */
    public function addHabilidad(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:asesor,mentor',
            'idUsuario' => 'required|integer',
            'idHabilidad' => 'required|integer',
        ]);

        $tipo = $request->input('tipo');
        $idUsuario = $request->input('idUsuario');
        $idHabilidad = $request->input('idHabilidad');

        try {
            if ($tipo === 'asesor') {
                $usuario = Asesor::find($idUsuario);
            } elseif ($tipo === 'mentor') {
                $usuario = Mentor::find($idUsuario);
            } else {
                return response()->json(['success' => false, 'message' => 'Tipo de usuario inválido.'], 400);
            }

            if (!$usuario) {
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
            }

            // Adjuntar la habilidad. El método attach() por defecto previene duplicados en la tabla pivote.
            $usuario->habilidades()->attach($idHabilidad);

            return response()->json(['success' => true, 'message' => 'Habilidad asignada con éxito.']);

        } catch (\Exception $e) {
            // Manejar errores de base de datos, como violación de clave primaria (si no se usara attach())
            return response()->json(['success' => false, 'message' => 'Error al asignar la habilidad: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Desasigna una habilidad de un asesor o mentor.
     */
    public function removeHabilidad(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:asesor,mentor',
            'idUsuario' => 'required|integer',
            'idHabilidad' => 'required|integer',
        ]);

        $tipo = $request->input('tipo');
        $idUsuario = $request->input('idUsuario');
        $idHabilidad = $request->input('idHabilidad');

        try {
            if ($tipo === 'asesor') {
                $usuario = Asesor::find($idUsuario);
            } elseif ($tipo === 'mentor') {
                $usuario = Mentor::find($idUsuario);
            } else {
                return response()->json(['success' => false, 'message' => 'Tipo de usuario inválido.'], 400);
            }

            if (!$usuario) {
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
            }

            // Desadjuntar la habilidad
            $usuario->habilidades()->detach($idHabilidad);

            return response()->json(['success' => true, 'message' => 'Habilidad desasignada con éxito.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al desasignar la habilidad: ' . $e->getMessage()], 500);
        }
    }
}