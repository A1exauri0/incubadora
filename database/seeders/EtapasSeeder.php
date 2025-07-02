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
                'nombre' => 'PENDIENTE',
                'descripcion' => 'Proyecto pendiente de revisión o acción.',
                'color' => 'Gris'
            ],
            [
                'idEtapa' => 2,
                'nombre' => 'V.º B.º Asesor',
                'descripcion' => 'Visto bueno del asesor.',
                'color' => 'Amarillo'
            ],
            [
                'idEtapa' => 3,
                'nombre' => 'V.º B.º Administrador',
                'descripcion' => 'Visto bueno del administrador.',
                'color' => 'Verde'
            ],
            [
                'idEtapa' => 4,
                'nombre' => 'Rechazado',
                'descripcion' => 'Proyecto rechazado.',
                'color' => 'Rojo'
            ]
        ]);
    }
}