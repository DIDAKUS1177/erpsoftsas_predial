<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrediosValidacionesQrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predios_validaciones_qr', function (Blueprint $table) {
            $table->id();
            $table->string('token', 96)->unique();
            $table->unsignedBigInteger('id_predio');
            $table->string('certificado_numero', 100);
            $table->string('propietario_principal', 255);
            $table->date('fecha_expedicion');
            $table->date('fecha_validez');
            $table->boolean('is_validated')->default(0);
            $table->timestamp('validated_at')->nullable();
            $table->string('validated_ip', 45)->nullable();
            $table->text('validated_user_agent')->nullable();
            $table->timestamps();
            $table->boolean('estado')->default(1);
            // add field that represents the max number of validations allowed
            $table->unsignedSmallInteger('max_validations')->default(5);
            // add field that counts the number of validations done
            $table->unsignedSmallInteger('validation_count')->default(0);

            $table->index('token');
            $table->index('id_predio');
            $table->index('certificado_numero');
            $table->index(['is_validated', 'fecha_validez']);

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
        Schema::dropIfExists('predios_validaciones_qr');
    }
}
