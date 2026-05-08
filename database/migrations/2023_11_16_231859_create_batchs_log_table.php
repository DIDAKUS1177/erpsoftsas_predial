<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batchs_log', function (Blueprint $table) {
            $table->id();
            $table->longText('descripcion');
            $table->unsignedBigInteger('id_batch');
            $table->unsignedBigInteger('id_predio');
            $table->timestamp('fecha')->useCurrent();

            $table->foreign('id_batch')->references('id')->on('batchs');
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
        Schema::dropIfExists('batchs_log');
    }
}
