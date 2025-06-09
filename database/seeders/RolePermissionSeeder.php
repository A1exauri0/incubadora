<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $rolesPermissions = [
            'admin' => ['mostrar admin'],
            'alumno' => ['mostrar alumno'],
            'asesor' => ['mostrar asesor'],
            'mentor' => ['mostrar mentor'],
            'emprendedor' => ['mostrar emprendedor'],
            'inversionista' => ['mostrar inversionista'],
        ];

        foreach ($rolesPermissions as $roleName => $permissions) {
            $role = Role::findByName($roleName);
            if ($role) {
                $role->syncPermissions($permissions);
            }
        }
    }
}