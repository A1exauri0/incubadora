<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // No olvides importar DB

class HabilidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('habilidad')->insert([
            [
                'nombre' => 'Programación en Python',
                'descripcion' => 'Desarrollo de aplicaciones y scripts con Python.'
            ],
            [
                'nombre' => 'Gestión de Proyectos',
                'descripcion' => 'Habilidad para planificar, ejecutar y cerrar proyectos exitosamente.'
            ],
            [
                'nombre' => 'Diseño UX/UI',
                'descripcion' => 'Creación de interfaces de usuario intuitivas y experiencias de usuario agradables.'
            ],
            [
                'nombre' => 'Marketing Digital',
                'descripcion' => 'Estrategias y ejecución de campañas en plataformas digitales.'
            ],
            [
                'nombre' => 'Análisis de Datos',
                'descripcion' => 'Extracción y procesamiento de datos para obtener insights.'
            ],
        ]);
    }
}