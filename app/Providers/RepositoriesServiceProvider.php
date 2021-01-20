<?php

namespace App\Providers;

use App\Repositories\Eloquent\RecipesRepository;
use App\Repositories\Interfaces\IUsersRepository;
use App\Repositories\Eloquent\UsersRepository;
use App\Repositories\Interfaces\IRecipesRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IUsersRepository::class, UsersRepository::class);
        $this->app->bind(IRecipesRepository::class, RecipesRepository::class);
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
