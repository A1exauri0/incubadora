<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // No olvides importar DB

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('color')->insert([
            ['nombre' => 'Azul', 'clase' => 'primary'],
            ['nombre' => 'Verde', 'clase' => 'success'],
            ['nombre' => 'Rojo', 'clase' => 'danger'],
            ['nombre' => 'Amarillo', 'clase' => 'warning'],
            ['nombre' => 'Celeste', 'clase' => 'info'],
            ['nombre' => 'Gris', 'clase' => 'secondary'],
            ['nombre' => 'Gris claro', 'clase' => 'light'],
            ['nombre' => 'Gris oscuro', 'clase' => 'dark'],
        ]);
    }
}