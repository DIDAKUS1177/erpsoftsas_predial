<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTiposIdentificacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_identificacion', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 50);
            $table->string('abreviacion', 3);
            $table->timestamps();
        });

        // Insert some stuff
        DB::table('tipos_identificacion')->insert(
            array(
                ['descripcion' => 'Cédula de ciudadania', 'abreviacion' => 'CC' ],
                ['descripcion' => 'Tarjeta de identidad', 'abreviacion' => 'TI' ],
                ['descripcion' => 'Pasaporte', 'abreviacion' => 'PA' ],
                ['descripcion' => 'Cédula de extranjeria', 'abreviacion' => 'CE' ]
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
        Schema::dropIfExists('tipos_identificacion');
    }
}
