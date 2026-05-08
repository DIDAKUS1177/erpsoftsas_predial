<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivosAsobancariaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivos_asobancaria', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->string('path');
            $table->string('name');
            $table->string('type');
            $table->string('size');
            $table->integer('total_registros');
            $table->integer('total_guardados');
            $table->integer('total_fallidos');
            $table->text('descripcion');
            $table->timestamps();
            $table->date('fecha_pago');
            $table->unsignedBigInteger('id_banco');

            $table->foreign('id_usuario')->references('id')->on('usuarios');
            $table->foreign('id_banco')->references('id')->on('bancos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archivos_asobancaria');
    }
}
