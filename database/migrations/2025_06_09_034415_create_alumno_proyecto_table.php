<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Asegúrate de que esta línea esté presente

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alumno_proyecto', function (Blueprint $table) {
            $table->char('clave_proyecto', 15);
            $table->char('no_control', 8);
            $table->dateTime('fecha_agregado')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('lider')->default(false);

            // Definición de las claves foráneas
            $table->foreign('no_control')
                ->references('no_control')
                ->on('alumno')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('clave_proyecto')
                ->references('clave_proyecto')
                ->on('proyecto')
                ->onUpdate('cascade')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumno_proyecto');
    }
};