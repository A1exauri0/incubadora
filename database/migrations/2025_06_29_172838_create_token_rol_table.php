<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('token_rol', function (Blueprint $table) {
            $table->id('idToken'); 
            $table->string('token')->unique(); 
            $table->string('correo')->unique(); 
            $table->unsignedBigInteger('rol'); 

            $table->timestamps();

            // Definir la clave foránea al ID de la tabla 'roles'
            $table->foreign('rol')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('token_rol');
    }
};

