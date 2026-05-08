<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDescuentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descuentos', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('anio');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('porcentaje', $precision = 5, $scale = 2);
            $table->timestamps();

            //$table->unique(['anio', 'fecha_limite']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('descuentos');
    }
}
