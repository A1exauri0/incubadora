<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categoria')->insert([
            ['idCategoria' => 12, 'nombre' => 'Cambio climático'],
            ['idCategoria' => 13, 'nombre' => 'Industria eléctrica y electrónica'],
            ['idCategoria' => 14, 'nombre' => 'Sector agroalimentario'],
            ['idCategoria' => 15, 'nombre' => 'Industrias creativas'],
            ['idCategoria' => 16, 'nombre' => 'Electromovilidad y ciudades inteligentes'],
            ['idCategoria' => 17, 'nombre' => 'Servicios para la salud'],
            ['idCategoria' => 18, 'nombre' => 'PENDIENTE'],
            ['idCategoria' => 19, 'nombre' => 'NODESS'],
        ]);
    }
}