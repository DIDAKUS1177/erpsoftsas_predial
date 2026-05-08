<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResolucionesIgacTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resoluciones_igac', function (Blueprint $table) {
            $table->id();
            $table->string('ano',4);
            $table->string('resolucion', 25);
            $table->date('fecha');
            $table->integer('consecutivo');
            $table->string('codigo',30);
            $table->string('codigoanterior',30);
            $table->string('tipo', 50);
            $table->string('tiporegistro', 50);
            $table->integer('numeroorden');
            $table->string('avaluoigac', 50);
            $table->string('area', 50);
            $table->string('nombre', 50);
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
        Schema::dropIfExists('resoluciones_igac');
    }
}
