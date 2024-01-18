<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers;

use App\Models\PrizeDrawingCalendar;
use Carbon\Carbon;
use MoonShine\MoonShineRequest;
use MoonShine\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class PrizeDrawingCalenderController extends MoonShineController
{
    public function massStore(MoonShineRequest $request): Response
    {
        $data = $request->request->all();
        $startedAt = Carbon::parse($data['started_at']);
        $repeatWeeks = (int) $data['repeat_weeks'];
        $prizes = array_reduce((array) $data['prizes'], function ($carry, $item) {
            $prize = $item['prize_id'];
            $carry[$prize] = $item;
            return $carry;
        }, []);
        for ($i = 0; $i < $repeatWeeks; $i++) {
            $date = $startedAt->copy()->addWeeks($i)->toDateTimeString();
            foreach($prizes as $prize)
            {
                $prize_id = $prize['prize_id'];
                $prize_number = $prize['number'];
                PrizeDrawingCalendar::create([
                    'prize_id' => $prize_id,
                    'number' => $prize_number,
                    'drawing_at' => $date,
                ]);
            }
        }
        $this->toast(__('ui.messages.added'));
        return back();
    }
}
