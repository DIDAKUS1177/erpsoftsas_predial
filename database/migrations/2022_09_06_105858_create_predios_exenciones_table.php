<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrediosExencionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predios_exenciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio');
            $table->smallInteger('exencion_anio');
            $table->float('porcentaje');
            $table->float('valor_concepto1');
            $table->float('valor_concepto2');
            $table->float('valor_concepto3');
            $table->float('valor_concepto4');
            $table->float('valor_concepto5');
            $table->float('valor_concepto6');
            $table->float('valor_concepto7');
            $table->float('valor_concepto8');
            $table->float('valor_concepto9');
            $table->float('valor_concepto10');
            $table->float('valor_concepto11');
            $table->float('valor_concepto12');
            $table->float('valor_concepto13');
            $table->float('valor_concepto14');
            $table->float('valor_concepto15');
            $table->float('valor_concepto16');
            $table->float('valor_concepto17');
            $table->float('valor_concepto18');
            $table->float('valor_concepto19');
            $table->float('valor_concepto20');
            $table->float('valor_concepto21');
            $table->float('valor_concepto22');
            $table->float('valor_concepto23');
            $table->float('valor_concepto24');
            $table->float('valor_concepto25');
            $table->float('valor_concepto26');
            $table->float('valor_concepto27');
            $table->float('valor_concepto28');
            $table->float('valor_concepto29');
            $table->float('valor_concepto30');
            $table->float('avaluo');
            $table->timestamps();


            $table->foreign('id_predio')->references('id')->on('predios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('predios_exenciones');
    }
}
