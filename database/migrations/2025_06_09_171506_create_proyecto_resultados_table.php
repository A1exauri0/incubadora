<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; 

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proyecto_resultados', function (Blueprint $table) {
            $table->increments('idResultado'); 
            $table->char('clave_proyecto', 15);   
            $table->string('descripcion', 100);    
            $table->dateTime('fecha_agregado')->default(DB::raw('CURRENT_TIMESTAMP')); 

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
        Schema::dropIfExists('proyecto_resultados');
    }
};