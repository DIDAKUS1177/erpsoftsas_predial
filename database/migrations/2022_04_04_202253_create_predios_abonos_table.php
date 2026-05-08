<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrediosAbonosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predios_abonos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio');
            $table->smallInteger('anio_abono');
            $table->string('factura_abono', 15);
            $table->decimal('valor_abono', $precision = 20, $scale = 2);
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
        Schema::dropIfExists('predios_abonos');
    }
}
