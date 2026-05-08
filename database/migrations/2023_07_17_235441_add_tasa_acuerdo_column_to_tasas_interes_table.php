<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTasaAcuerdoColumnToTasasInteresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasas_interes', function (Blueprint $table) {
            $table->decimal('tasa_acuerdo', $precision = 20, $scale = 12)->nullable()->default(0);
            /*
            ALTER TABLE [erpsofts_predial].[dbo].[tasas_interes]
            ADD tasa_acuerdo decimal(20, 12)
            DEFAULT (0)
            WITH VALUES;
            */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasas_interes', function (Blueprint $table) {
            $table->dropColumn('tasa_acuerdo');
        });
    }
}
