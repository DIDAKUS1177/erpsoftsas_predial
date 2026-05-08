<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrediosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_predio', 40);
            $table->string('codigo_predio_anterior', 40);
            $table->string('direccion', 512);
            $table->decimal('avaluo', $precision = 20, $scale = 2);
            $table->smallInteger('ultimo_anio_pago');
            $table->unsignedBigInteger('id_zona'); // RURAL, URBANO

            $table->char('tipo', 2)->nullable();
            $table->char('zona', 2)->nullable();

            $table->char('sector', 2)->nullable();

            $table->char('comuna', 2)->nullable();
            $table->char('barrio', 2)->nullable();

            $table->char('manzana', 4)->nullable();

            $table->char('predio', 4)->nullable();
            $table->char('terreno', 4)->nullable();

            $table->char('mejora', 3)->nullable();

            $table->char('condicion', 1)->nullable();
            $table->char('edificio_torre', 2)->nullable();
            $table->char('piso', 2)->nullable();
            $table->char('propiedad', 4)->nullable();

            $table->decimal('area_metros', $precision = 12, $scale = 2);
            $table->decimal('area_construida', $precision = 12, $scale = 2);
            $table->decimal('area_hectareas', $precision = 12, $scale = 2);
            $table->decimal('tarifa_actual', $precision = 12, $scale = 2);

            $table->boolean('estado')->default(1);

            $table->timestamps();

            $table->tinyInteger('ind_excento_impuesto')->default(0);
            $table->tinyInteger('ind_excento_car')->default(0);
            $table->tinyInteger('ind_excento')->default(0);
            $table->tinyInteger('ind_eliminado')->default(0);
            $table->timestamp('fecha_eliminacion')->nullable();
            $table->string('descripcion_eliminacion', 100)->nullable();
            $table->tinyInteger('ind_ley1995')->default(0);
            $table->tinyInteger('ind_plusvalia')->default(0);
            $table->tinyInteger('ind_invalidez')->default(0);

            $table->foreign('id_zona')->references('id')->on('zonas');

            $table->unique('codigo_predio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('predios');
    }
}
