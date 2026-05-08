<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opciones', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 100);
            $table->string('url', 128);
            $table->unsignedBigInteger('opcion_padre')->nullable();
            $table->string('icono', 30);
            $table->string('color', 20);
            $table->char('estado', 1)->default('A');
            $table->string('descripcion', 1024);
            $table->boolean('resolucion_elimina')->nullable();
            $table->boolean('resolucion_edita')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opciones');
    }
}
