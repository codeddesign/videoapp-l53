<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Monolog\Handler\LogEntriesHandler;

class LogEntriesServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //Logentries should just run on staging or production
        if (! in_array($this->app->environment(), ['staging', 'production'])) {
            return;
        }

        $token = config('services.logentries.token');

        $this->app->configureMonologUsing(function ($monolog) use ($token) {
            $logEntriesHandler = new LogEntriesHandler($token);
            $monolog->pushHandler($logEntriesHandler);
        });
    }
}
