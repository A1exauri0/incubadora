<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habilidad extends Model
{
    use HasFactory;

    protected $table = 'habilidad'; // Especifica el nombre de la tabla
    protected $primaryKey = 'idHabilidad'; // Especifica la clave primaria
    public $timestamps = false; // Si tu tabla no tiene columnas created_at y updated_at

    /**
     * The asesores that have this Habilidad.
     */
    public function asesores()
    {
        return $this->belongsToMany(Asesor::class, 'habilidad_asesor', 'idHabilidad', 'idAsesor');
    }

    /**
     * The mentores that have this Habilidad.
     */
    public function mentores()
    {
        return $this->belongsToMany(Mentor::class, 'habilidad_mentor', 'idHabilidad', 'idMentor');
    }
}