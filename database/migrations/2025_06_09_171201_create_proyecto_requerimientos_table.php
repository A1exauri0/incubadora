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
        Schema::create('proyecto_requerimientos', function (Blueprint $table) {
            $table->increments('idRequerimiento');
            $table->char('clave_proyecto', 15); 
            $table->string('descripcion', 50);    
            $table->string('cantidad', 50);      

            // Definición de la clave foránea
            $table->foreign('clave_proyecto')
                  ->references('clave_proyecto')->on('proyecto')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyecto_requerimientos');
    }
};