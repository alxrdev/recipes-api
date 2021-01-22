<?php

namespace App\Providers;

use App\Services\Auth\AuthenticateUserService;
use App\Services\Auth\Interfaces\IAuthenticateUserService;
use App\Services\Recipes\CreateRecipeService;
use App\Services\Recipes\Interfaces\ICreateRecipeService;
use App\Services\Recipes\Interfaces\IHandleRecipeImagesService;
use App\Services\Recipes\HandleRecipeImagesService;
use App\Services\Recipes\Interfaces\IUpdateRecipeService;
use App\Services\Recipes\UpdateRecipeService;
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

        // Recipes services
        $this->app->bind(ICreateRecipeService::class, CreateRecipeService::class);
        $this->app->bind(IUpdateRecipeService::class, UpdateRecipeService::class);
        $this->app->bind(IHandleRecipeImagesService::class, HandleRecipeImagesService::class);
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
