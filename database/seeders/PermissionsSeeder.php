<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            [
                'id' => 6,
                'name' => 'mostrar admin',
                'guard_name' => 'web',
                'created_at' => Carbon::parse('2025-01-05 00:09:56'),
                'updated_at' => Carbon::parse('2025-01-05 00:09:56'),
            ],
            [
                'id' => 7,
                'name' => 'mostrar alumno',
                'guard_name' => 'web',
                'created_at' => Carbon::parse('2025-01-05 00:09:57'),
                'updated_at' => Carbon::parse('2025-01-05 00:09:57'),
            ],
            [
                'id' => 8,
                'name' => 'mostrar asesor',
                'guard_name' => 'web',
                'created_at' => Carbon::parse('2025-01-05 00:09:57'),
                'updated_at' => Carbon::parse('2025-01-05 00:09:57'),
            ],
            [
                'id' => 9,
                'name' => 'mostrar mentor',
                'guard_name' => 'web',
                'created_at' => Carbon::parse('2025-01-05 00:09:57'),
                'updated_at' => Carbon::parse('2025-01-05 00:09:57'),
            ],
            [
                'id' => 10,
                'name' => 'mostrar emprendedor',
                'guard_name' => 'web',
                'created_at' => Carbon::parse('2025-01-05 00:09:57'),
                'updated_at' => Carbon::parse('2025-01-05 00:09:57'),
            ],
            [
                'id' => 11,
                'name' => 'mostrar inversionista',
                'guard_name' => 'web',
                'created_at' => Carbon::parse('2025-01-05 00:09:57'),
                'updated_at' => Carbon::parse('2025-01-05 00:09:57'),
            ],
        ]);
    }
}