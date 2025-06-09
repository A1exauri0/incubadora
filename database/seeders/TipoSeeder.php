<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo')->insert([
            ['idTipo' => 12, 'nombre' => 'Innovación'],
            ['idTipo' => 13, 'nombre' => 'Residencia profesional'],
            ['idTipo' => 14, 'nombre' => 'Servicio social'],
            ['idTipo' => 15, 'nombre' => 'Gobierno'],
        ]);
    }
}