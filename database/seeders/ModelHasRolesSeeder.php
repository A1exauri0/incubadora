<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Asegúrate de importar tu modelo User
use Spatie\Permission\Models\Role; // Asegúrate de importar el modelo Role de Spatie

class ModelHasRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuarios de prueba, eliminar al terminar de probar el sistema

        // Para el role_id 1 (admin)
        $adminUser = User::find(23); // Asumiendo que el usuario con id 23 es Adrián Alejandro
        $adminRole = Role::find(1); // Asumiendo que el role con id 1 es 'admin'

        if ($adminUser && $adminRole) {
            $adminUser->assignRole($adminRole);
        }

        // Para el role_id 2 (alumno)
        $alumnoUser = User::find(30); 
        $alumnoRole = Role::find(2);

        if ($alumnoUser && $alumnoRole) {
            $alumnoUser->assignRole($alumnoRole);
        }

        //Para el role_id 3 (asesor)
        $asesorUser = User::find(31);
        $asesorRole = Role::find(3);

        if ($asesorUser && $asesorRole) {
            $asesorUser->assignRole($asesorRole);
        }

    }
}