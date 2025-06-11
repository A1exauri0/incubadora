<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesor extends Model
{
    use HasFactory;

    protected $table = 'asesor'; // Especifica el nombre de la tabla
    protected $primaryKey = 'idAsesor'; // Especifica la clave primaria
    public $timestamps = false; // Si tu tabla no tiene columnas created_at y updated_at

    /**
     * The habilidades that belong to the Asesor.
     */
    public function habilidades()
    {
        // El primer parámetro es el modelo relacionado (Habilidad)
        // El segundo es el nombre de la tabla pivote (habilidad_asesor)
        // El tercero es la clave foránea del modelo actual en la tabla pivote (idAsesor)
        // El cuarto es la clave foránea del modelo relacionado en la tabla pivote (idHabilidad)
        return $this->belongsToMany(Habilidad::class, 'habilidad_asesor', 'idAsesor', 'idHabilidad');
    }
}