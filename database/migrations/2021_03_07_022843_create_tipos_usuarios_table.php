<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTiposUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 50);
            $table->string('view', 50);
            $table->timestamps();
        });

        // Insert some stuff
        DB::table('tipos_usuarios')->insert(
            array(
                ['descripcion' => 'Administrador', 'view' => 'admin' ],
                ['descripcion' => 'Otro', 'view' => 'other' ]
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
        Schema::dropIfExists('tipos_usuarios');
    }
}
