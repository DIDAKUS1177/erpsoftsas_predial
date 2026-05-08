<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrediosPropietariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predios_propietarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio');
            $table->unsignedBigInteger('id_propietario');
            $table->char('jerarquia', 3); // numero propietario
            $table->timestamps();

            $table->foreign('id_predio')->references('id')->on('predios');
            $table->foreign('id_propietario')->references('id')->on('propietarios');

            //$table->unique(['id_predio', 'id_propietario']); // una persona puee vender un predio y luego volverlo a comprar
            $table->unique(['id_predio', 'jerarquia']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('predios_propietarios');
    }
}
