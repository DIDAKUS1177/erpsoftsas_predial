<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batchs', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('anio');
            $table->unsignedBigInteger('id_predio_inicial');
            $table->unsignedBigInteger('id_predio_final');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamp('fecha_inicio')->useCurrent();
            $table->timestamp('fecha_fin')->nullable();
            $table->boolean('estado')->default(1); // 1 = En ejecucion, 0 = Finalizado
            $table->int('calculados')->default(0);
            $table->int('no_calculados')->default(0);
            $table->int('por_calcular')->default(0);

            $table->foreign('id_predio_inicial')->references('id')->on('predios');
            $table->foreign('id_predio_final')->references('id')->on('predios');
            $table->foreign('id_usuario')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batchs');
    }
}
