<?php

namespace Database\Seeders;


use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@g.com'],  // Busca por el correo electrónico
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin'), // Hasheando la contraseña
            ]
        )->assignRole('admin');


        User::updateOrCreate(
            ['email' => 'alumno@g.com'],  // Busca por el correo electrónico
            [
                'name' => 'alumno',
                'password' => Hash::make('alumno'), // Hasheando la contraseña
            ]
        )->assignRole('alumno');
    }
}
