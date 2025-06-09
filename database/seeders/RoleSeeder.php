<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission; // Ya no necesitamos Permission aquí
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles o actualizar si ya existen
        $roles = [
            'admin',
            'alumno',
            'asesor',
            'mentor',
            'emprendedor',
            'inversionista',
        ];

        foreach ($roles as $roleName) { // Cambié $role a $roleName para mayor claridad
            Role::updateOrCreate(['name' => $roleName, 'guard_name' => 'web']); // Aseguramos guard_name
        }

    }
}