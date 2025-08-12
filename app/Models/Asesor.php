<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesor extends Model
{
    use HasFactory;

    protected $table = 'asesor';

    protected $primaryKey = 'idAsesor';

    public $timestamps = false; // Si no tienes timestamps en la tabla

    protected $fillable = ['nombre', 'telefono', 'correo_electronico', 'fecha_agregado'];

    // Define la relación Many-to-Many con la tabla 'habilidad'
    public function habilidades()
    {
        return $this->belongsToMany(Habilidad::class, 'habilidad_asesor', 'idAsesor', 'idHabilidad');
    }
}