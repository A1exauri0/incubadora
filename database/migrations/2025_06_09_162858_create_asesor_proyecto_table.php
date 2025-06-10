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
        Schema::create('asesor_proyecto', function (Blueprint $table) {
            $table->unsignedBigInteger('idAsesor');
            $table->char('clave_proyecto', 50);
            $table->timestamp('fecha_agregado')->useCurrent();

            // Claves foráneas
            $table->foreign('idAsesor')
                  ->references('idAsesor')
                  ->on('asesor')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('clave_proyecto')
                  ->references('clave_proyecto')
                  ->on('proyecto')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesor_proyecto');
    }
};