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
        Schema::create('asesor', function (Blueprint $table) {
            $table->id('idAsesor'); // Auto-increment primary key
            $table->string('nombre', 50);
            $table->char('telefono', 10);
            $table->string('correo_electronico', 50);
            $table->timestamp('fecha_agregado')->useCurrent(); // Default current timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesor');
    }
};
