<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(\App\Providers\ServiceServiceProvider::class);
        $this->app->register(\App\Providers\RepositoryServiceProvider::class);
    }

    public function boot(): void {}
}
