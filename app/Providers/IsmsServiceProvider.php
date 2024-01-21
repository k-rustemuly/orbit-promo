<?php

namespace App\Providers;

use App\Services\IsmsApi;
use Illuminate\Support\ServiceProvider;

class IsmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(IsmsApi::class, static function ($app) {
            return new IsmsApi($app['config']['services.isms']);
        });
    }

    public function provides(): array
    {
        return [
            IsmsApi::class,
        ];
    }
}
