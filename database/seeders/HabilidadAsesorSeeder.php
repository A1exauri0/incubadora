<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HabilidadAsesorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('habilidad_asesor')->insert([
            // Asesor 2 tiene habilidades de Programación en Python y Gestión de Proyectos
            ['idHabilidad' => 1, 'idAsesor' => 2],
            ['idHabilidad' => 2, 'idAsesor' => 2], 

            // Asesor 3 tiene habilidades de Diseño UX/UI y Marketing Digital
            ['idHabilidad' => 3, 'idAsesor' => 3], 
            ['idHabilidad' => 4, 'idAsesor' => 3],

            // Asesor 4 tiene habilidades de Análisis de Datos y Programación en Python (si se repiten)
            ['idHabilidad' => 5, 'idAsesor' => 4], 
            ['idHabilidad' => 1, 'idAsesor' => 4],
        ]);
    }
}