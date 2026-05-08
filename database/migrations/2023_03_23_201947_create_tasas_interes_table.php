<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasasInteresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasas_interes', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('anio');
            $table->smallInteger('mes');
            $table->decimal('tasa_diaria', $precision = 20, $scale = 12);
            $table->decimal('tasa_mensual', $precision = 20, $scale = 12);
            $table->timestamps();

            $table->unique(['anio', 'mes']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasas_interes');
    }
}
