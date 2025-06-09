<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProyectoRequerimientosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('proyecto_requerimientos')->insert([
            [
                'idRequerimiento' => 1,
                'clave_proyecto' => '0000000000029',
                'descripcion' => 'requerimiento 1',
                'cantidad' => 'uno',
            ],
            [
                'idRequerimiento' => 2,
                'clave_proyecto' => '0000000000029',
                'descripcion' => 'requerimiento 2',
                'cantidad' => 'dos',
            ],
            [
                'idRequerimiento' => 3,
                'clave_proyecto' => '0000000000029',
                'descripcion' => 'requerimiento 3',
                'cantidad' => 'tres',
            ],
        ]);
    }
}