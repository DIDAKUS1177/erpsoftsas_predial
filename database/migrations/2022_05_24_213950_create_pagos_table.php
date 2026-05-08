<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_pago');

            $table->string('codigo_barras', 128);
            $table->string('numero_recibo', 128);
            $table->unsignedBigInteger('id_predio');
            $table->decimal('valor_facturado', $precision = 20, $scale = 2);
            $table->smallInteger('anio_pago');
            $table->date('fecha_factura');
            $table->unsignedBigInteger('id_banco_factura');

            $table->unsignedBigInteger('id_banco_archivo')->nullable();
            $table->char('paquete_archivo', 2)->nullable();
            $table->char('origen', 1)->nullable(); // M = carga manual, A = carga archivo
            $table->timestamps();

            $table->foreign('id_predio')->references('id')->on('predios');
            $table->foreign('id_banco_archivo')->references('id')->on('bancos');
            $table->foreign('id_banco_factura')->references('id')->on('bancos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}
