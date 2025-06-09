<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon; // Para manejar las fechas

class MentorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mentor')->insert([
            [
                'nombre' => 'Dr. Juan Pérez',
                'fecha_agregado' => Carbon::now()->subMonths(3), // Ejemplo: Agregado hace 3 meses
            ],
            [
                'nombre' => 'Mtra. Ana Gómez',
                'fecha_agregado' => Carbon::now()->subMonths(2), // Ejemplo: Agregado hace 2 meses
            ],
            [
                'nombre' => 'Ing. Luis Ramírez',
                'fecha_agregado' => Carbon::now()->subMonths(1), // Ejemplo: Agregado hace 1 mes
            ],
            [
                'nombre' => 'Lic. Sofía Martínez',
                'fecha_agregado' => Carbon::now(), // Ejemplo: Agregado ahora
            ],
        ]);
    }
}