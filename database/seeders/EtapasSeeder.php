<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Importa DB

class EtapasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('etapas')->insert([
            [
                'idEtapa' => 1,
                'nombre' => 'Inicio',
                'descripcion' => 'Proyecto en fase de inicio.',
                'color' => 'Verde'
            ],
            [
                'idEtapa' => 2,
                'nombre' => 'Desarrollo',
                'descripcion' => 'Proyecto en fase de desarrollo.',
                'color' => 'Amarillo'
            ],
            [
                'idEtapa' => 3,
                'nombre' => 'Final',
                'descripcion' => 'Proyecto en fase final.',
                'color' => 'Rojo'
            ],
            [
                'idEtapa' => 4,
                'nombre' => 'PENDIENTE',
                'descripcion' => 'Proyecto pendiente de revisión o acción.',
                'color' => 'Gris'
            ],
        ]);
    }
}