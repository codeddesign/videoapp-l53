<?php

namespace App\Providers;

use App\JWT\JWT;
use App\JWT\JWTAuth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->app['auth']->extend('jwt', function ($app, $name, array $config) {
            return new JWTAuth(
                new JWT($this->app['log']),
                $this->app['auth']->createUserProvider($config['provider']),
                $app['request']);
        });
    }
}
