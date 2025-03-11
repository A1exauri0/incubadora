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
        Schema::create('habilidad', function (Blueprint $table) {
            $table->id('idHabilidad'); // Auto-increment primary key (unsigned int)
            $table->string('nombre', 50)->nullable(); // varchar(50) with utf8mb4_0900_ai_ci collation, nullable
            $table->string('descripcion', 200)->nullable(); // varchar(200) with utf8mb4_0900_ai_ci collation, nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habilidad');
    }
};
