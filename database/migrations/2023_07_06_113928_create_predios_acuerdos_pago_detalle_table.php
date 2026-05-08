<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrediosAcuerdosPagoDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predios_acuerdos_pago_detalle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_acuerdo');
            $table->unsignedBigInteger('id_predio_pago');
            $table->tinyInteger('cuota_numero');
            $table->decimal('valor_cuota', $precision = 20, $scale = 12);
            $table->date('fecha_pago');
            $table->timestamps();

            $table->foreign('id_acuerdo')->references('id')->on('predios_acuerdos_pago');
            $table->foreign('id_predio_pago')->references('id')->on('predios_pagos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('predios_acuerdos_pago_detalle');
    }
}
