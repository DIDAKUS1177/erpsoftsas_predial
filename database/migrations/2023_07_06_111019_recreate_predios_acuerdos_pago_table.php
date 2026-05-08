<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreatePrediosAcuerdosPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('predios_acuerdos_pago');
        Schema::create('predios_acuerdos_pago', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio');
            $table->string('numero_acuerdo', 10);
            $table->smallInteger('anio_inicial_acuerdo');
            $table->smallInteger('anio_final_acuerdo');
            $table->boolean('responsable_propietario_acuerdo')->default(0);
            $table->string('identificacion_acuerdo', 30)->nullable();
            $table->string('nombre_acuerdo', 128)->nullable();
            $table->string('direccion_acuerdo', 128)->nullable();
            $table->string('telefono_acuerdo', 10)->nullable();
            $table->tinyInteger('cuotas_acuerdo')->default(1);
            $table->string('numero_resolucion_acuerdo', 20);
            $table->tinyInteger('dia_pago_acuerdo')->default(1);
            $table->decimal('abono_inicial_acuerdo', $precision = 20, $scale = 8)->default(0);
            $table->boolean('estado_acuerdo')->default(1); // 1=activo, 0=inactivo
            $table->unsignedBigInteger('id_usuario_crea');
            $table->unsignedBigInteger('id_usuario_anula')->nullable();
            $table->date('fecha_anulacion')->nullable();
            $table->timestamps();

            $table->foreign('id_predio')->references('id')->on('predios');
            $table->foreign('id_usuario_crea')->references('id')->on('usuarios');
            $table->foreign('id_usuario_anula')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('predios_acuerdos_pago');
    }
}
