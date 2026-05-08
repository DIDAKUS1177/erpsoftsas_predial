<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('15o25', function ($attribute, $value, $parameters, $validator) {
            return strlen($value) == 15 || strlen($value) == 25;
        });

        Validator::extend('3o13', function ($attribute, $value, $parameters, $validator) {
            return strlen($value) == 3 || strlen($value) == 13;
        });

        Blade::directive('money', function ($amount) {
                return "<?php
                    if($amount < 0) {
                        echo '-$ ' . number_format(abs($amount), 2);
                    } else {
                        echo '$ ' . number_format($amount, 2);
                    }
                ?>";
        });

        Blade::directive('money2', function ($amount) {
            return "<?php
                if($amount < 0) {
                    echo '-$' . number_format(abs($amount), 2);
                } else {
                    echo '$' . number_format($amount, 2);
                }
            ?>";
    });
    }
}
