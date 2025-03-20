<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OrthancSyncService;

class OrthancServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(OrthancSyncService::class, function ($app) {
            return new OrthancSyncService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
