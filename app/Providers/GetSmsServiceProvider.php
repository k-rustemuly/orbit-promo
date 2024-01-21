<?php

namespace App\Providers;

use App\Services\GetSmsApi;
use Illuminate\Support\ServiceProvider;

class GetSmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(GetSmsApi::class, static function ($app) {
            return new GetSmsApi($app['config']['services.getsms']);
        });
    }

    public function provides(): array
    {
        return [
            GetSmsApi::class,
        ];
    }
}
