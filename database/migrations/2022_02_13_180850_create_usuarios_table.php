<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('documento', 13); // identificacion
            $table->unsignedBigInteger('id_tipo_identificacion');
            $table->string('nombres', 60); // nombres
            $table->string('apellidos', 60); // apellidos
            $table->date('fecha_nacimiento'); // fecha de nacimiento
            $table->string('correo_electronico', 128); // correo electronico
            $table->string('direccion', 128); // direccion
            $table->string('telefono', 10); // telefono
            $table->char('estado', 1)->default('A'); // estado, activo  A / inactivo I. Valor por defecto: A
            $table->string('password', 128); // Contraseña, Encriptar con MD5
            $table->unsignedBigInteger('id_tipo_usuario');
            $table->timestamps();

            $table->foreign('id_tipo_identificacion')->references('id')->on('tipos_identificacion');
            $table->foreign('id_tipo_usuario')->references('id')->on('tipos_usuarios');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
