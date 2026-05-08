<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrediosCambiosTarifasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predios_cambios_tarifas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio');
            $table->decimal('tarifa_anterior', $total = 5, $places = 2);
            $table->decimal('tarifa_nueva', $total = 5, $places = 2);
            $table->string('file_name', 1024)->nullable();
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
        Schema::dropIfExists('predios_cambios_tarifas');
    }
}
