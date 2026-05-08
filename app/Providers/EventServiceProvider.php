<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\CalculoBatch;
use App\Listeners\EjecutarCalculo;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CalculoBatch::class => [
            EjecutarCalculo::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('App\Events\CalculoBatch', 'App\Listeners\EjecutarCalculo');

        // Event::listen(
        //     CalculoBatch::class,
        //     [EjecutarCalculo::class, 'handle']
        // );

        // Event::listen(queueable(function (CalculoBatch $event) {
        //     //
        // }));
    }
}
