<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Dompdf\Css\Color;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
                //Tablas padre
            CategoriaSeeder::class,
            CarreraSeeder::class,
            ColorSeeder::class,
            TipoSeeder::class,
            AlumnoSeeder::class,
            AlumnoSemestreSeeder::class,
            AsesorSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            EspecialidadesSeeder::class,
            EtapasSeeder::class,
            HabilidadSeeder::class,
            MentorSeeder::class,
            PermissionsSeeder::class,
            ServicioSeeder::class,
                //Tablas hijo
            ProyectoSeeder::class,
            AlumnoProyectoSeeder::class,
            AsesorProyectoSeeder::class,
            HabilidadAsesorSeeder::class,
            MentorProyectoSeeder::class,
            ProyectoRequerimientosSeeder::class,
            ProyectoResultadosSeeder::class,
            RolePermissionSeeder::class,
            ModelHasRolesSeeder::class,
        ]);

    }
}
