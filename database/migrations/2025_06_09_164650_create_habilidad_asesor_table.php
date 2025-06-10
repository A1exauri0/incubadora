<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('habilidad_asesor', function (Blueprint $table) {
            $table->unsignedBigInteger('idHabilidad');
            $table->unsignedBigInteger('idAsesor');

            // Definición de las claves foráneas
            $table->foreign('idHabilidad')
                ->references('idHabilidad')->on('habilidad')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('idAsesor')
                ->references('idAsesor')->on('asesor')
                ->onDelete('cascade')
                ->onUpdate('cascade');

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