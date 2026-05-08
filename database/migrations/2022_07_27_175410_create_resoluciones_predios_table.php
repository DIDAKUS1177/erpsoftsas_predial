<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResolucionesPrediosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resoluciones_predios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_resolucion');
            $table->unsignedBigInteger('id_predio');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamp('fecha')->useCurrent();
            $table->string('descripcion', 512);
            $table->timestamps();

            $table->foreign('id_resolucion')->references('id')->on('resoluciones');
            $table->foreign('id_predio')->references('id')->on('predios');
            $table->foreign('id_usuario')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resoluciones_predios');
    }
}
