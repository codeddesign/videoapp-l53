<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('emails', function ($attribute, $value, $parameters) {
            $rules  = [
                'email' => 'required|email',
            ];
            $emails = explode(',', $value);
            foreach ($emails as $email) {
                $email     = trim($email);
                $data      = [
                    'email' => $email,
                ];
                $validator = Validator::make($data, $rules);
                if ($validator->fails()) {
                    return false;
                }
            }

            return true;
        });

        //$this->logQueries();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }

    protected function logQueries()
    {
        if (! $this->app->environment('local', 'testing')) {
            return;
        }

        $formatSql = function ($sql, $bindings) {
            $needle = '?';
            foreach ($bindings as $replace) {
                $pos = strpos($sql, $needle);
                if ($pos !== false) {
                    if (gettype($replace) === 'string' || true) {
                        $replace = ' \''.$replace.'\' ';
                    }
                    $sql = substr_replace($sql, $replace, $pos, strlen($needle));
                }
            }

            return $sql;
        };

        \DB::listen(function ($query) use ($formatSql) {
            \Log::info($formatSql($query->sql, $query->bindings));
            \Log::info($query->time);
        });
    }
}
