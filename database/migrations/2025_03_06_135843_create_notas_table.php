<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_predio_pago');
            $table->unsignedBigInteger('id_usuario');
            $table->smallInteger('anio');
            $table->string('factura_pago', 128);
            $table->float('prev_valor_concepto1')->default(0);
            $table->float('prev_valor_concepto2')->default(0);
            $table->float('prev_valor_concepto3')->default(0);
            $table->float('prev_valor_concepto4')->default(0);
            $table->float('prev_valor_concepto5')->default(0);
            $table->float('prev_valor_concepto6')->default(0);
            $table->float('prev_valor_concepto7')->default(0);
            $table->float('prev_valor_concepto8')->default(0);
            $table->float('prev_valor_concepto9')->default(0);
            $table->float('prev_valor_concepto10')->default(0);
            $table->float('prev_valor_concepto11')->default(0);
            $table->float('prev_valor_concepto12')->default(0);
            $table->float('prev_valor_concepto13')->default(0);
            $table->float('prev_valor_concepto14')->default(0);
            $table->float('prev_valor_concepto15')->default(0);
            $table->float('prev_valor_concepto16')->default(0);
            $table->float('prev_valor_concepto17')->default(0);
            $table->float('prev_valor_concepto18')->default(0);
            $table->float('prev_valor_concepto19')->default(0);
            $table->float('prev_valor_concepto20')->default(0);
            $table->float('prev_valor_concepto21')->default(0);
            $table->float('prev_valor_concepto22')->default(0);
            $table->float('prev_valor_concepto23')->default(0);
            $table->float('prev_valor_concepto24')->default(0);
            $table->float('prev_valor_concepto25')->default(0);
            $table->float('prev_valor_concepto26')->default(0);
            $table->float('prev_valor_concepto27')->default(0);
            $table->float('prev_valor_concepto28')->default(0);
            $table->float('prev_valor_concepto29')->default(0);
            $table->float('prev_valor_concepto30')->default(0);
            $table->float('prev_total_calculo')->default(0);
            $table->float('prev_total_dos')->default(0);
            $table->float('prev_total_tres')->default(0);
            $table->float('valor_concepto1')->default(0);
            $table->float('valor_concepto2')->default(0);
            $table->float('valor_concepto3')->default(0);
            $table->float('valor_concepto4')->default(0);
            $table->float('valor_concepto5')->default(0);
            $table->float('valor_concepto6')->default(0);
            $table->float('valor_concepto7')->default(0);
            $table->float('valor_concepto8')->default(0);
            $table->float('valor_concepto9')->default(0);
            $table->float('valor_concepto10')->default(0);
            $table->float('valor_concepto11')->default(0);
            $table->float('valor_concepto12')->default(0);
            $table->float('valor_concepto13')->default(0);
            $table->float('valor_concepto14')->default(0);
            $table->float('valor_concepto15')->default(0);
            $table->float('valor_concepto16')->default(0);
            $table->float('valor_concepto17')->default(0);
            $table->float('valor_concepto18')->default(0);
            $table->float('valor_concepto19')->default(0);
            $table->float('valor_concepto20')->default(0);
            $table->float('valor_concepto21')->default(0);
            $table->float('valor_concepto22')->default(0);
            $table->float('valor_concepto23')->default(0);
            $table->float('valor_concepto24')->default(0);
            $table->float('valor_concepto25')->default(0);
            $table->float('valor_concepto26')->default(0);
            $table->float('valor_concepto27')->default(0);
            $table->float('valor_concepto28')->default(0);
            $table->float('valor_concepto29')->default(0);
            $table->float('valor_concepto30')->default(0);
            $table->float('total_calculo')->default(0);
            $table->float('total_dos')->default(0);
            $table->float('total_tres')->default(0);
            $table->string('file_name', 1024)->nullable();
            $table->timestamps();
            $table->boolean('estado')->default(1);

            $table->foreign('id_predio_pago')->references('id')->on('predios_pagos');
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
        Schema::dropIfExists('notas');
    }
}
