<?php

namespace App\Listeners;

use App\Events\CalculoBatch;
use App\Exports\ExportPrediosPagos;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class EjecutarCalculo implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CalculoBatch  $event
     * @return void
     */
    public function handle(CalculoBatch $event)
    {
        Log::info('Start batch.');
        // $files = Storage::files('framework/cache/laravel-excel');

        DB::select("SET NOCOUNT ON; EXEC SP_CALCULO_PREDIAL_BATCH ?,?,?,?",
            array(
                $event->anio,
                $event->predio_inicial,
                $event->predio_final,
                $event->id_usuario
            )
        );

        // Store on default disk
        // Excel::store(new ExportPrediosPagos(), 'cartera/reporte-cartera.xlsx', 'public');

        Log::info('End batch.');
    }

    /**
     * Handle a job failure.
     *
     * @param  \App\Events\CalculoBatch  $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(CalculoBatch $event, $exception)
    {
        Log::info('Inside listener ex.');
        Log::info($exception);
    }
}
