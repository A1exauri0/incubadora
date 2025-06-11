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
        Schema::create('habilidad_mentor', function (Blueprint $table) {
            // Clave foránea para la tabla 'mentor'
            // Asumiendo que 'mentor' tiene una PK 'idMentor' de tipo unsignedBigInteger
            $table->unsignedBigInteger('idMentor');
            $table->foreign('idMentor')
                  ->references('idMentor') // Asegúrate de que 'idMentor' es el nombre real de la PK en la tabla 'mentor'
                  ->on('mentor')           // Asegúrate de que 'mentor' es el nombre real de tu tabla de mentores
                  ->onDelete('cascade');   // Opcional: Elimina las relaciones si se elimina el mentor

            // Clave foránea para la tabla 'habilidad' (que ya tienes)
            $table->unsignedBigInteger('idHabilidad');
            $table->foreign('idHabilidad')
                  ->references('idHabilidad') // Nombre de la PK en tu tabla 'habilidad'
                  ->on('habilidad')           // Nombre de tu tabla de habilidades
                  ->onDelete('cascade');     // Opcional: Elimina las relaciones si se elimina la habilidad

            // Define una clave primaria compuesta para asegurar que cada par (mentor, habilidad) sea único
            $table->primary(['idMentor', 'idHabilidad']);

            // Opcional: Si quieres registrar cuándo se asignó una habilidad a un mentor
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habilidad_mentor');
    }
};