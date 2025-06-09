<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumnoSemestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('alumno_semestre')->insert([
            ['idSemestre' => 1, 'nombre' => 'Primer semestre'],
            ['idSemestre' => 2, 'nombre' => 'Segundo semestre'],
            ['idSemestre' => 3, 'nombre' => 'Tercer semestre'],
            ['idSemestre' => 4, 'nombre' => 'Cuarto semestre'],
            ['idSemestre' => 5, 'nombre' => 'Quinto semestre'],
            ['idSemestre' => 6, 'nombre' => 'Sexto semestre'],
            ['idSemestre' => 7, 'nombre' => 'Séptimo semestre'],
            ['idSemestre' => 8, 'nombre' => 'Octavo semestre'],
            ['idSemestre' => 9, 'nombre' => 'Noveno semestre'],
            ['idSemestre' => 10, 'nombre' => 'Décimo semestre'],
        ]);
    }
}