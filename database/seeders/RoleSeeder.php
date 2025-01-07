<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
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

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role]);
        }

        // Crear permisos generales o actualizar si ya existen
        $generalPermissions = [
            'mostrar admin',
            'mostrar alumno',
            'mostrar asesor',
            'mostrar mentor',
            'mostrar emprendedor',
            'mostrar inversionista',
        ];

        foreach ($generalPermissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }

        // Asignar permisos a roles
        Role::findByName('admin')->syncPermissions([
            'mostrar admin',
        ]);

        Role::findByName('alumno')->syncPermissions([
            'mostrar alumno',
        ]);

        Role::findByName('asesor')->syncPermissions([
            'mostrar asesor',
        ]);

        Role::findByName('mentor')->syncPermissions([
            'mostrar mentor',
        ]);

        Role::findByName('emprendedor')->syncPermissions([
            'mostrar emprendedor',
        ]);

        Role::findByName('inversionista')->syncPermissions([
            'mostrar inversionista',
        ]);

        /*
        Nota: Este seeder solo creará o actualizará roles y permisos
        sin eliminar asociaciones existentes.
        */
    }
}
