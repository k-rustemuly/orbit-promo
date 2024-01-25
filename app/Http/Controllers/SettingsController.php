<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsUpdateRequest;
use App\Services\InstantPrizeService;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use MoonShine\Http\Controllers\MoonShineController;

class SettingsController extends MoonShineController
{
    public function update(GeneralSettings $settings, SettingsUpdateRequest $request, InstantPrizeService $service)
    {
        $data = $request->validated();
        $settings->game_max_coins = $data['game_max_coins'];
        $settings->receipt_life = $data['receipt_life'];
        $settings->referal_life = $data['referal_life'];
        $settings->game_max_coins = $data['game_max_coins'];
        $promotion = $data['promotion'];
        $startDate = Carbon::parse($promotion['start_date']);
        $endDate = Carbon::parse($promotion['end_date']);
        $settings->start_date = $startDate;
        $settings->end_date = $endDate;
        $settings->save();
        $service->distribute($startDate, $endDate);
        $this->toast(__('ui.messages.saved'));
        return back();
    }
}
