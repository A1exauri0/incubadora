<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon; // Para manejar las fechas
use Illuminate\Support\Facades\Hash; // Para hashing de contraseñas

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            /* Usuarios de prueba, eliminar al terminar de probar el sistema */
            [
                'id' => 23,
                'name' => 'Adrián Alejandro',
                'email' => 'l21270669@tuxtla.tecnm.mx',
                'email_verified_at' => Carbon::parse('2025-02-15 09:08:47'),
                'password' => Hash::make('11111111'),
                'remember_token' => null,
                'created_at' => Carbon::parse('2025-01-26 09:21:40'),
                'updated_at' => Carbon::parse('2025-02-15 09:08:47'),
            ],
            [
                'id' => 30,
                'name' => 'Jose Estrada',
                'email' => 'l21270650@tuxtla.tecnm.mx',
                'email_verified_at' => Carbon::parse('2025-02-24 00:22:55'),
                'password' => Hash::make('11111111'),
                'remember_token' => null,
                'created_at' => Carbon::parse('2025-02-24 00:22:47'),
                'updated_at' => Carbon::parse('2025-02-24 00:22:55'),
            ],
                        [
                'id' => 31,
                'name' => 'Asesor',
                'email' => 'servicios030630@gmail.com',
                'email_verified_at' => Carbon::parse('2025-02-24 00:22:55'),
                'password' => Hash::make('11111111'),
                'remember_token' => null,
                'created_at' => Carbon::parse('2025-02-24 00:22:47'),
                'updated_at' => Carbon::parse('2025-02-24 00:22:55'),
            ]
        ]);
    }
}