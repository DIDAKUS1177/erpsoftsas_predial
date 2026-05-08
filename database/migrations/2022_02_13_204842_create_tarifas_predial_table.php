<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarifasPredialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarifas_predial', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('anio');
            //$table->smallInteger('meses_amnistia');
            $table->string('codigo', 10);
            $table->tinyInteger('rango');
            $table->string('descripcion', 128);
            $table->decimal('avaluo_inicial', $precision = 20, $scale = 2);
            $table->decimal('avaluo_final', $precision = 20, $scale = 2);
            $table->decimal('tarifa', $precision = 12, $scale = 2);
            $table->decimal('porcentaje_car', $precision = 5, $scale = 2);
            $table->tinyInteger('estrato');
            $table->string('destino', 128);
            $table->tinyInteger('grupo_tarifa');
            $table->timestamps();

            $table->unique(['codigo', 'anio']);
            $table->unique(['rango', 'anio']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarifas_predial');
    }
}
