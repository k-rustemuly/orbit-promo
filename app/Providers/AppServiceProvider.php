<?php

namespace App\Providers;

use App\Models\Invitation;
use App\Models\User;
use App\Models\Voucher;
use App\Observers\InvitationObserver;
use App\Observers\UserObserver;
use App\Observers\VoucherObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Voucher::observe(VoucherObserver::class);
        User::observe(UserObserver::class);
        Invitation::observe(InvitationObserver::class);
    }
}
