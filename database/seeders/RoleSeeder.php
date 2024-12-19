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
        // Crear roles
        $adminRole = Role::updateOrCreate(['name' => 'admin']);
        $alumnoRole = Role::updateOrCreate(['name' => 'alumno']);
        $asesorRole = Role::updateOrCreate(['name' => 'asesor']);
        $mentorRole = Role::updateOrCreate(['name' => 'mentor']);
        $emprendedorRole = Role::updateOrCreate(['name' => 'emprendedor']);
        $inversionistaRole = Role::updateOrCreate(['name' => 'inversionista']);

        // Crear permisos
        $permissions = [
            'ver proyectos',
            'crear proyectos',
            'editar proyectos',
            'eliminar proyectos',
            'gestionar usuarios'
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }

        // Asignar permisos a roles
        $adminRole->syncPermissions([
            'ver proyectos',
            'crear proyectos',
            'editar proyectos',
            'eliminar proyectos',
            'gestionar usuarios'
        ]);

        $alumnoRole->syncPermissions([
            'ver proyectos',
            'crear proyectos'
        ]);

        $asesorRole->syncPermissions([
            'ver proyectos',
            'editar proyectos'
        ]);

        $mentorRole->syncPermissions([
            'ver proyectos'
        ]);

        $emprendedorRole->syncPermissions([
            'ver proyectos'
        ]);

        $inversionistaRole->syncPermissions([
            'ver proyectos'
        ]);

        /*
        Nota: Para aplicar los cambios ejecuta:
        php artisan migrate:fresh --seed
        */
    }
}
