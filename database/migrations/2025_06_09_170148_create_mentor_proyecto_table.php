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
        Schema::create('mentor_proyecto', function (Blueprint $table) {
            $table->unsignedInteger('idMentor')->nullable();
            $table->char('clave_proyecto', 50)->nullable();
            $table->dateTime('fecha_agregado')->default(DB::raw('CURRENT_TIMESTAMP'));

            // Definición de las claves foráneas
            $table->foreign('idMentor')
                  ->references('idMentor')->on('mentor')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

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
        Schema::dropIfExists('mentor_proyecto');
    }
};