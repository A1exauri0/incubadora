<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon; // Para manejar las fechas

class MentorProyectoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('mentor_proyecto')->insert([
            [
                'idMentor' => 1,
                'clave_proyecto' => '0000000000050', 
                'fecha_agregado' => Carbon::now()->subDays(10),
            ],
            [
                'idMentor' => 2,
                'clave_proyecto' => '0000000000049', 
                'fecha_agregado' => Carbon::now()->subDays(5),
            ],
            [
                'idMentor' => 3,
                'clave_proyecto' => '0000000000048', 
                'fecha_agregado' => Carbon::now()->subDays(7),
            ],
            [
                'idMentor' => 4,
                'clave_proyecto' => '0000000000047', 
                'fecha_agregado' => Carbon::now()->subDays(2),
            ],
        ]);
    }
}