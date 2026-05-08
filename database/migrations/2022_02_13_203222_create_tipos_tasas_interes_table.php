<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTiposTasasInteresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_tasas_interes', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 30);
            $table->timestamps();
        });

        // Insert some stuff
        DB::table('tipos_tasas_interes')->insert(
            array(
                ['descripcion' => 'Diaria' ],
                ['descripcion' => 'Mensual' ]
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
        Schema::dropIfExists('tipos_tasas_interes');
    }
}
