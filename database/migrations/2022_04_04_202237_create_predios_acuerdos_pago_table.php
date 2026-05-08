<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrediosAcuerdosPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predios_acuerdos_pago', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio');
            $table->string('numero', 10);
            $table->tinyInteger('cuota');
            $table->date('fecha');
            $table->decimal('capital', $precision = 20, $scale = 2);
            $table->decimal('interes', $precision = 20, $scale = 2);
            $table->decimal('valor', $precision = 20, $scale = 2);
            $table->string('factura', 15);
            $table->date('fecha_pago');
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
        Schema::dropIfExists('predios_acuerdos_pago');
    }
}
