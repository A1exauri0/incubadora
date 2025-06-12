<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use HasFactory;

    protected $table = 'mentor'; // Especifica el nombre de la tabla
    protected $primaryKey = 'idMentor'; // Especifica la clave primaria
    public $timestamps = false; // Si tu tabla no tiene columnas created_at y updated_at

    /**
     * The habilidades that belong to the Mentor.
     */
    public function habilidades()
    {
        return $this->belongsToMany(Habilidad::class, 'habilidad_mentor', 'idMentor', 'idHabilidad');
    }
}