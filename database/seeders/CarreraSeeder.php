<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Importa DB

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('carrera')->insert([
            [
                'clave' => '0000000000000',
                'nombre' => 'PENDIENTE',
                'fecha_agregado' => '2024-06-01 18:42:16'
            ],
            [
                'clave' => 'DALB-2010-12',
                'nombre' => 'Doctorado en Ciencias de los Alimentos y Biotecnología',
                'fecha_agregado' => '2024-08-28 10:42:27'
            ],
            [
                'clave' => 'DING-2010-13',
                'nombre' => 'Doctorado en Ciencias de la Ingeniería',
                'fecha_agregado' => '2024-08-28 10:42:09'
            ],
            [
                'clave' => 'IBQA-2010-207',
                'nombre' => 'Ingeniería Bioquímica',
                'fecha_agregado' => '2024-03-18 15:32:56'
            ],
            [
                'clave' => 'IELC-2010-211',
                'nombre' => 'Ingeniería Electrónica',
                'fecha_agregado' => '2024-03-18 15:32:10'
            ],
            [
                'clave' => 'IELE-2010-209',
                'nombre' => 'Ingeniería Eléctrica',
                'fecha_agregado' => '2024-03-18 15:32:35'
            ],
            [
                'clave' => 'IGEM-2009-201',
                'nombre' => 'Ingeniería en Gestión Empresarial',
                'fecha_agregado' => '2024-03-18 15:33:32'
            ],
            [
                'clave' => 'IIND-2010-227',
                'nombre' => 'Ingeniería Industrial',
                'fecha_agregado' => '2024-03-18 15:31:50'
            ],
            [
                'clave' => 'ILOG-2009-202',
                'nombre' => 'Ingeniería en Logística',
                'fecha_agregado' => '2024-03-18 15:36:22'
            ],
            [
                'clave' => 'IMCT-2010-229',
                'nombre' => 'Ingeniería Mecatrónica',
                'fecha_agregado' => '2024-03-18 15:29:54'
            ],
            [
                'clave' => 'IMEC-2010-228',
                'nombre' => 'Ingeniería Mecánica',
                'fecha_agregado' => '2024-03-18 15:31:03'
            ],
            [
                'clave' => 'IQUI-2010-232',
                'nombre' => 'Ingeniería Química',
                'fecha_agregado' => '2024-03-18 15:33:10'
            ],
            [
                'clave' => 'ISIC-2010-224',
                'nombre' => 'Ingeniería en Sistemas Computacionales',
                'fecha_agregado' => '2024-03-17 20:56:12'
            ],
            [
                'clave' => 'MCIBQ-2011-20',
                'nombre' => 'Maestría en Ciencias en Ingeniería Bioquímica',
                'fecha_agregado' => '2024-08-27 12:19:01'
            ],
            [
                'clave' => 'MCIMC-2011-21',
                'nombre' => 'Maestría en Ciencias en Ingeniería Mecatrónica',
                'fecha_agregado' => '2024-08-27 11:39:40'
            ],
            [
                'clave' => 'MPADM-2011-26',
                'nombre' => 'Maestría en Administración',
                'fecha_agregado' => '2024-08-28 10:47:58'
            ],
        ]);
    }
}