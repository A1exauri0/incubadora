<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proyecto', function (Blueprint $table) {
            $table->char('clave_proyecto', 15)->primary();
            $table->string('nombre', 50)->nullable(false);
            $table->string('nombre_descriptivo', 100)->nullable(false);
            $table->string('descripcion', 800)->nullable(false);

            $table->foreignId('categoria')->constrained('categoria', 'idCategoria')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedInteger('tipo');
            $table->foreign('tipo')->references('idTipo')->on('tipo')
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->integer('etapa')->default(4);
            $table->string('video', 255)->default('Sin video');
            $table->string('area_aplicacion', 50)->default('Sin asignar');
            $table->string('naturaleza_tecnica', 50)->default('Sin asignar');
            $table->string('objetivo', 600)->default('Sin asignar');

            $table->dateTime('fecha_agregado')->default(DB::raw('CURRENT_TIMESTAMP'));

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyecto');
    }
};