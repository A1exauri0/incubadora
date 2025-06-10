<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('servicio')->insert([
            [
                'idServicio' => 1,
                'nombre' => 'Asesoría de Negocios',
                'descripcion' => 'Servicio de consultoría para desarrollo de planes de negocio.',
            ],
            [
                'idServicio' => 2,
                'nombre' => 'Mentoría Tecnológica',
                'descripcion' => 'Apoyo y guía en el desarrollo de soluciones tecnológicas innovadoras.',
            ],
            [
                'idServicio' => 3,
                'nombre' => 'Espacio de Coworking',
                'descripcion' => 'Acceso a instalaciones compartidas para emprendedores.',
            ],
            [
                'idServicio' => 4,
                'nombre' => 'Financiamiento Semilla',
                'descripcion' => 'Oportunidades de inversión inicial para startups.',
            ],
        ]);
    }
}