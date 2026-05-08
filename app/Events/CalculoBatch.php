<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CalculoBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $anio;
    public $predio_inicial;
    public $predio_final;
    public $id_usuario;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($anio, $predio_inicial, $predio_final, $id_usuario)
    {
        Log::info('Inside event.');
        $this->anio = $anio;
        $this->predio_inicial = $predio_inicial;
        $this->predio_final = $predio_final;
        $this->id_usuario = $id_usuario;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
