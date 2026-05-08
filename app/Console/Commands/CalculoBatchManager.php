<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CalculoBatchManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'execute:batch {--anio} {--predio_inicial} {--predio_final} {--id_usuario}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecucion de calculo predial batch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Inside command');
        DB::select("SET NOCOUNT ON; EXEC SP_CALCULO_PREDIAL_BATCH ?,?,?,?", array($this->argument('anio'), $this->argument('predio_inicial'), $this->argument('predio_final'), $this->argument('id_usuario')));
        // return 0;
    }
}
