<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpcionesTipoUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opciones_tipos_usuarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_opcion');
            $table->unsignedBigInteger('id_tipo_usuario');
            $table->smallInteger('jerarquia');
            $table->timestamps();

            $table->foreign('id_opcion')->references('id')->on('opciones');
            $table->foreign('id_tipo_usuario')->references('id')->on('tipos_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opciones_tipos_usuarios');
    }
}
