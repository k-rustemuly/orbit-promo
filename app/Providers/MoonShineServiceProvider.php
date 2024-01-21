<?php

declare(strict_types=1);

namespace App\Providers;

use App\MoonShine\Resources\InstantPrizeResource;
use App\MoonShine\Resources\PrizeDrawingCalendarResource;
use App\MoonShine\Resources\PrizeResource;
use App\MoonShine\Resources\ReceiptStatusResource;
use App\MoonShine\Resources\VoucherResource;
use Illuminate\Http\Request;
use MoonShine\Menu\MenuGroup;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    protected function resources(): array
    {
        return [
            new MoonShineUserRoleResource()
        ];
    }

    protected function pages(): array
    {
        return [];
    }

    protected function menu(): array
    {
        return [
            MenuItem::make(__('moonshine::ui.resource.admins_title'), new MoonShineUserResource())
                ->canSee(fn(Request $request) => $request->user('moonshine')?->moonshine_user_role_id == 1),

            MenuGroup::make(__('ui.menu.prizes'), [
                MenuItem::make(__('ui.menu.weekly_prizes'), new PrizeResource()),
                MenuItem::make(__('ui.menu.instant_prizes'), new InstantPrizeResource()),
            ],
            'heroicons.gift'),

            MenuItem::make(__('ui.menu.vouchers'), new VoucherResource(), 'heroicons.ticket'),

            MenuItem::make(__('ui.menu.prize_drawing_calendars'), new PrizeDrawingCalendarResource(), 'heroicons.calendar-days'),

            MenuItem::make(__('ui.menu.receipt_statuses'), new ReceiptStatusResource(), 'heroicons.list-bullet'),

        ];
    }

    /**
     * @return array{css: string, colors: array, darkColors: array}
     */
    protected function theme(): array
    {
        return [];
    }
}
