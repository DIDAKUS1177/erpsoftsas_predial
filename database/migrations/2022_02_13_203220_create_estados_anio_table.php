<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateEstadosAnioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estados_anio', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 50);
            $table->timestamps();
        });

        // Insert some stuff
        DB::table('estados_anio')->insert(
            array(
                ['descripcion' => 'Activo' ],
                ['descripcion' => 'Cerrado' ]
            ),
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estados_anio');
    }
}
