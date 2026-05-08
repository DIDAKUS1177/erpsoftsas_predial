<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrediosDatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predios_datos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio');
            $table->unsignedBigInteger('id_tipo_predio');
            $table->string('matricula_inmobiliaria', 15);
            $table->decimal('avaluo_presente_anio', $precision = 20, $scale = 2);
            $table->tinyInteger('excento_impuesto')->nullable();
            $table->unsignedBigInteger('id_clase_predio');
            $table->unsignedBigInteger('id_clase_mutacion');
            $table->tinyInteger('predio_incautado')->nullable();
            $table->tinyInteger('aplica_ley44')->nullable();
            $table->timestamps();

            $table->foreign('id_predio')->references('id')->on('predios');
            $table->foreign('id_tipo_predio')->references('id')->on('tipos_predio');
            $table->foreign('id_clase_predio')->references('id')->on('clases_predio');
            $table->foreign('id_clase_mutacion')->references('id')->on('clases_mutacion');

            //$table->unique(['id_predio', 'id_tipo_predio']);
            //$table->unique(['id_predio', 'matricula_inmobiliaria']);
            $table->unique('id_predio');
            $table->unique('matricula_inmobiliaria');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('predios_datos');
    }
}
