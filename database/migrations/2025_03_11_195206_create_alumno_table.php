<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alumno', function (Blueprint $table) {
            $table->char('no_control', 8)->primary(); // Primary key 'no_control'
            $table->string('nombre', 50);
            $table->string('carrera', 75);
            $table->string('correo_institucional', 100)->unique();
            $table->char('telefono', 10)->default('');
            $table->integer('semestre')->default(0);
            $table->timestamp('fecha_agregado')->useCurrent(); // Default current timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumno');
    }
};
