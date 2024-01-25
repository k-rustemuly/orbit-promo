<?php

namespace App\Services;

use App\Models\InstantPrize;
use Carbon\Carbon;
use DateInterval;

class InstantPrizeService
{

    public function distribute(Carbon $startDate, Carbon $endDate): void
    {
        $totalPrizes = InstantPrize::notWon()->count();

        $dateInterval = $startDate->toPeriod($endDate, new DateInterval('P1D'));

        $totalDays = $dateInterval->count();

        $prizesPerDay = ceil($totalPrizes / $totalDays);

        $prizesDistribution = [];

        foreach ($dateInterval as $date) {
            $dailyPrizes = min($prizesPerDay, $totalPrizes);
            $prizesDistribution[$date->format('Y-m-d')] = $dailyPrizes;
            $totalPrizes -= $dailyPrizes;
        }

        if ($totalPrizes > 0) {
            end($prizesDistribution);
            $lastDay = key($prizesDistribution);
            $prizesDistribution[$lastDay] += $totalPrizes;
        }

        $prizesDistributionDateTimes = [];
        foreach ($prizesDistribution as $date => $dailyPrizes) {
            $prizesDistributionDateTimes = array_merge($prizesDistributionDateTimes, $this->distributePrizesOverDay($dailyPrizes, $date));
        }

        shuffle($prizesDistributionDateTimes);

        InstantPrize::notWon()
            ->chunk(100, function ($records) use (&$prizesDistributionDateTimes) {
                foreach ($records as $record) {
                    if(!empty($prizesDistributionDateTimes)) {
                        $record->update(['draw_date' => array_shift($prizesDistributionDateTimes)]);
                    }
                }
            });

    }

    public function distributePrizesOverDay($dailyPrizes, $date)
    {
        $prizesDistribution = [];

        for ($i = 0; $i < $dailyPrizes; $i++) {
            $randomTime = Carbon::parse($date)->addHours(rand(8, 20))->addMinutes(rand(0, 59))->format('Y-m-d H:i:s');
            $prizesDistribution[] = $randomTime;
        }

        return $prizesDistribution;
    }
}
