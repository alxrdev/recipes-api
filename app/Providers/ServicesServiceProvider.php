<?php

namespace App\Providers;

use App\Services\Auth\AuthenticateUserService;
use App\Services\Auth\Interfaces\IAuthenticateUserService;
use App\Services\Users\CreateUserService;
use App\Services\Users\Interfaces\ICreateUserService;
use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Users services
        $this->app->bind(ICreateUserService::class, CreateUserService::class);

        // Auth services
        $this->app->bind(IAuthenticateUserService::class, AuthenticateUserService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
