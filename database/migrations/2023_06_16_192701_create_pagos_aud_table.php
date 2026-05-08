<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosAudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_aud', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pago');
            $table->date('fecha_pago');
            $table->string('codigo_barras', 128)->nullable();
            $table->string('numero_recibo', 128);
            $table->unsignedBigInteger('id_predio');
            $table->decimal('valor_facturado', $precision = 20, $scale = 2);
            $table->smallInteger('anio_pago');
            $table->date('fecha_factura');
            $table->unsignedBigInteger('id_banco_factura');
            $table->unsignedBigInteger('id_banco_archivo')->nullable();
            $table->char('paquete_archivo', 2)->nullable();
            $table->char('origen', 1)->nullable();
            $table->unsignedBigInteger('id_usuario_elimina')->nullable();
            $table->unsignedBigInteger('id_usuario_edita')->nullable();
            $table->timestamps();

            $table->foreign('id_usuario_elimina')->references('id')->on('usuarios');
            $table->foreign('id_usuario_edita')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos_aud');
    }
}
