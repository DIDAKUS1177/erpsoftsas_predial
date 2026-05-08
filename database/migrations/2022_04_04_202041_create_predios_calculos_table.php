<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrediosCalculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predios_calculos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio');
            $table->tinyInteger('estrato');
            $table->unsignedBigInteger('id_tarifa_predial');
            $table->smallInteger('destino_economico');
            $table->string('numero_resolucion', 10);
            $table->string('numero_ultima_factura', 15);
            $table->tinyInteger('uso_suelo'); // SI, NO
            $table->timestamps();

            $table->foreign('id_predio')->references('id')->on('predios');
            $table->foreign('id_tarifa_predial')->references('id')->on('tarifas_predial');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('predios_calculos');
    }
}
