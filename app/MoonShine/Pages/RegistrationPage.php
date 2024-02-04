<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\User;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use MoonShine\Metrics\LineChartMetric;
use MoonShine\Pages\Page;

class RegistrationPage extends Page
{
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return __('ui.menu.registration');
    }

    public function components(): array
	{
        $settings = app(GeneralSettings::class);
        $startTime = $settings->start_date;
        $endTime = $settings->end_date;
        $nextMonday = $startTime->copy()->startOfWeek(Carbon::MONDAY);
        $allMondays = [];
        while ($nextMonday->lte($endTime)) {
            $allMondays[$nextMonday->format('d.m.Y')] = 0;
            $nextMonday->addWeek();
        }
		return [
            LineChartMetric::make(__('ui.messages.users'))
                ->withoutSortKeys()
                ->line([
                    __('ui.messages.registration_users') =>
                        collect($allMondays)
                        ->merge(
                            User::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as count')
                                ->groupBy(DB::raw('WEEK(created_at, 1)'))
                                ->get()
                                ->mapWithKeys(function ($item) {
                                    return [
                                        \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week - 1)->format('d.m.Y') => $item->count
                                    ];
                                })
                        )
                        ->toArray(),
                    __('ui.messages.not_verified_users') =>
                        collect($allMondays)
                            ->merge(User::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as count')
                                    ->whereNull('phone_number_verified_at')
                                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                                    ->get()
                                    ->mapWithKeys(function ($item) {
                                        return [
                                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => $item->count
                                        ];
                                    })
                            )
                        ->toArray(),
                ],[
                    'red', 'blue'
                ])
        ];
	}
}
