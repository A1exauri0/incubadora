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
        Schema::create('etapas', function (Blueprint $table) {
            $table->id('idEtapa'); // Auto-increment primary key
            $table->string('nombre', 50); // varchar(50) with utf8mb4_0900_ai_ci collation
            $table->string('descripcion', 50); // varchar(50) with utf8mb4_0900_ai_ci collation
            $table->string('color', 50); // varchar(50)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etapas');
    }
};
