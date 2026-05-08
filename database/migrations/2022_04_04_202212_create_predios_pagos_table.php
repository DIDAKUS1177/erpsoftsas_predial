<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrediosPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predios_pagos', function (Blueprint $table) {
            $table->integer('id');
            $table->unsignedBigInteger('id_predio');
            $table->smallInteger('ultimo_anio');
            $table->decimal('valor_pago', $precision = 20, $scale = 2);
            $table->date('fecha_pago');
            $table->string('factura_pago', 15);
            $table->unsignedBigInteger('id_banco');
            $table->tinyInteger('acuerdo_pago'); // SI, NO
            $table->timestamps();

            $table->foreign('id_predio')->references('id')->on('predios');
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
        Schema::dropIfExists('predios_pagos');
    }
}
