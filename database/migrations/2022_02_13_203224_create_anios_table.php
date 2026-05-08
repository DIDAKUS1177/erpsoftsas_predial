<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAniosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anios', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('anio');
            $table->unsignedBigInteger('id_estado');
            $table->smallInteger('meses_amnistia');
            $table->unsignedBigInteger('id_tipo_tasa_interes');
            $table->unsignedBigInteger('numero_factura_inicial');
            $table->unsignedBigInteger('numero_factura_actual');
            $table->unsignedBigInteger('consecutivo_paz');
            $table->timestamps();

            $table->foreign('id_estado')->references('id')->on('estados_anio');
            $table->foreign('id_tipo_tasa_interes')->references('id')->on('tipos_tasas_interes');

            $table->unique('anio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anios');
    }
}
