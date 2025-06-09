<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon; 

class ProyectoResultadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('proyecto_resultados')->insert([
            [
                'idResultado' => 4,
                'clave_proyecto' => '0000000000029', 
                'descripcion' => 'resultado 1',
                'fecha_agregado' => Carbon::parse('2024-11-10 12:37:14'),
            ],
            [
                'idResultado' => 5,
                'clave_proyecto' => '0000000000029',
                'descripcion' => 'resultado 2',
                'fecha_agregado' => Carbon::parse('2024-11-10 12:37:24'),
            ],
            [
                'idResultado' => 6,
                'clave_proyecto' => '0000000000029',
                'descripcion' => 'resultado 3',
                'fecha_agregado' => Carbon::parse('2024-11-10 12:37:36'),
            ],
        ]);
    }
}