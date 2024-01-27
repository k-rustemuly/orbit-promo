<?php

namespace App\Providers;

use App\Models\Invitation;
use App\Models\Receipt;
use App\Models\User;
use App\Models\Voucher;
use App\Observers\InvitationObserver;
use App\Observers\ReceiptObserver;
use App\Observers\UserObserver;
use App\Observers\VoucherObserver;
use App\Services\Rgl;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Rgl::class, static function ($app) {
            return new Rgl($app['config']['services.rgl']);
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Voucher::observe(VoucherObserver::class);
        User::observe(UserObserver::class);
        Invitation::observe(InvitationObserver::class);
        Receipt::observe(ReceiptObserver::class);
    }
}
