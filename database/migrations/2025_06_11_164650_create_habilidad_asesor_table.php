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
        Schema::create('habilidad_asesor', function (Blueprint $table) {
            // Clave foránea para la tabla 'asesor'
            // Asumiendo que 'asesor' tiene una PK 'idAsesor' de tipo unsignedBigInteger
            $table->unsignedBigInteger('idAsesor');
            $table->foreign('idAsesor')
                  ->references('idAsesor') // Asegúrate de que 'idAsesor' es el nombre real de la PK en la tabla 'asesor'
                  ->on('asesor')           // Asegúrate de que 'asesor' es el nombre real de tu tabla de asesores
                  ->onDelete('cascade');   // Opcional: Elimina las relaciones si se elimina el asesor

            // Clave foránea para la tabla 'habilidad' (que ya tienes)
            $table->unsignedBigInteger('idHabilidad');
            $table->foreign('idHabilidad')
                  ->references('idHabilidad') // Nombre de la PK en tu tabla 'habilidad'
                  ->on('habilidad')           // Nombre de tu tabla de habilidades
                  ->onDelete('cascade');     // Opcional: Elimina las relaciones si se elimina la habilidad

            // Define una clave primaria compuesta para asegurar que cada par (asesor, habilidad) sea único
            $table->primary(['idAsesor', 'idHabilidad']);

            // Opcional: Si quieres registrar cuándo se asignó una habilidad a un asesor
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habilidad_asesor');
    }
};