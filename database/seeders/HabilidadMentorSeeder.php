<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Importar la fachada DB
use Illuminate\Support\Arr; // Importar la clase Arr para métodos útiles

class HabilidadMentorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Opcional: Puedes truncar la tabla antes de sembrar para evitar duplicados.
        // DB::table('habilidad_mentor')->truncate();

        // Obtener los IDs de los primeros 3 mentores
        $mentorIds = DB::table('mentor')->pluck('idMentor')->take(3)->toArray();

        // Obtener los IDs de las primeras 2 habilidades
        $habilidadIdsAvailable = DB::table('habilidad')->pluck('idHabilidad')->take(2)->toArray();

        // Verificar si tenemos suficientes datos para sembrar
        if (empty($mentorIds)) {
            $this->command->warn("Advertencia: No se encontraron mentores. Asegúrate de que la tabla 'mentor' tenga al menos 3 registros.");
            return;
        }

        if (count($habilidadIdsAvailable) < 2) {
            $this->command->warn("Advertencia: No se encontraron al menos 2 habilidades. Asegúrate de que la tabla 'habilidad' tenga al menos 2 registros.");
            return;
        }

        $data = [];
        // Asignar 1 o 2 habilidades aleatorias a cada uno de los 3 mentores seleccionados
        foreach ($mentorIds as $mentorId) {
            // Seleccionar cuántas habilidades asignar a este mentor (entre 1 y 2)
            $numHabilidadesToAssign = rand(1, count($habilidadIdsAvailable));

            // Seleccionar habilidades únicas aleatoriamente del pool disponible
            $selectedHabilidades = Arr::random($habilidadIdsAvailable, $numHabilidadesToAssign);

            // Asegurarse de que $selectedHabilidades siempre sea un array para el foreach
            if (!is_array($selectedHabilidades)) {
                $selectedHabilidades = [$selectedHabilidades];
            }

            foreach ($selectedHabilidades as $habilidadId) {
                $data[] = [
                    'idMentor' => $mentorId,
                    'idHabilidad' => $habilidadId,
                ];
            }
        }

        // Usar insertOrIgnore para que no falle si ya existen entradas duplicadas
        DB::table('habilidad_mentor')->insertOrIgnore($data);

        $this->command->info('Tabla habilidad_mentor sembrada con ' . count($data) . ' registros (sin duplicados preexistentes).');
    }
}