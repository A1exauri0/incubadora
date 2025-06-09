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
            [
                'id' => 23,
                'name' => 'Adrián Alejandro',
                'email' => 'l21270669@tuxtla.tecnm.mx',
                'email_verified_at' => Carbon::parse('2025-02-15 09:08:47'),
                'password' => '$2y$12$JUXltUxmM0NCzYCvoikdAO4ZuhUjAfEliEaKyi6UYHj/XrfYuqcfC', // Contraseña ya hasheada
                'remember_token' => null,
                'created_at' => Carbon::parse('2025-01-26 09:21:40'),
                'updated_at' => Carbon::parse('2025-02-15 09:08:47'),
            ],
            [
                'id' => 30,
                'name' => 'Jose Estrada',
                'email' => 'l21270650@tuxtla.tecnm.mx',
                'email_verified_at' => Carbon::parse('2025-02-24 00:22:55'),
                'password' => '$2y$12$8hMWYsbf38l2epEokFG0fu67wTiCmJqftbp1n9nLfsWBNWWAnebO', // Contraseña ya hasheada
                'remember_token' => null,
                'created_at' => Carbon::parse('2025-02-24 00:22:47'),
                'updated_at' => Carbon::parse('2025-02-24 00:22:55'),
            ],
        ]);
    }
}