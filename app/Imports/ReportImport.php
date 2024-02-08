<?php

namespace App\Imports;

use App\Models\Game;
use App\Models\Invitation;
use App\Models\Receipt;
use App\Models\ReceiptStatus;
use App\Models\User;
use App\Services\GameService;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;

class ReportImport implements WithEvents
{

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {
                $templateFile = new LocalTemporaryFile(storage_path('report.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);

                $this->totalSheet($event->writer->getSheetByIndex(1));
                $this->lastSheet($event->writer->getSheetByIndex(2));

                $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

                return $event->getWriter()->getSheetByIndex(0);
            },
        ];
    }

    private function totalSheet($sheet){
        $uniqueUsersCount = User::verified()->count();
        $gamesCount = Game::finished()->count();
        $uniqueGamers = Game::finished()->distinct('user_id')->count();
        $gamesFirstLevel = Game::finished()->beforeLevel(1)->distinct('user_id')->count();
        $uniqueGamersNextLevel = User::verified()->nextLevel()->count();
        $scanCount = Receipt::count();
        $acceptedCount = Receipt::where('receipt_status_id', ReceiptStatus::ACCEPTED)->count();
        $rejectedCount = Receipt::where('receipt_status_id', ReceiptStatus::NOT_FOUND)->count();
        $uniqueScannedUsersCount = Receipt::distinct('user_id')->count();
        $invitationCount = Invitation::count();
        $uniqueInvitationCount = Invitation::distinct('owner_id')->count();
        $activeUsersCount =  DB::table('receipts')
            ->join('games', 'receipts.user_id', '=', 'games.user_id')
            ->select('receipts.user_id')
            ->groupBy('receipts.user_id')
            ->havingRaw('COUNT(DISTINCT receipts.id) >= 1')
            ->havingRaw('COUNT(DISTINCT games.id) >= 3')
            ->count();

        $sheet->setCellValue('B14', $uniqueUsersCount);
        $sheet->setCellValue('B15', $gamesCount);
        $sheet->setCellValue('B16', $this->percentage($uniqueGamers, $uniqueUsersCount));
        $sheet->setCellValue('B17', round($gamesCount/$uniqueGamers));
        $sheet->setCellValue('B18', round($gamesCount/$uniqueUsersCount));
        $sheet->setCellValue('B19', $this->percentage(GameService::getTotalReturningPlayers(), $uniqueGamers));
        $sheet->setCellValue('B20', $this->percentage($gamesFirstLevel, $gamesCount));
        $sheet->setCellValue('B21', GameService::uniquePlayersFirstLevelCount());
        $sheet->setCellValue('B22', $this->percentage($gamesCount - $gamesFirstLevel, $gamesCount));
        $sheet->setCellValue('B23', $uniqueGamersNextLevel);
        $sheet->setCellValue('B24', round(($gamesCount - $gamesFirstLevel)/$uniqueGamers));
        $sheet->setCellValue('B25', $invitationCount);
        $sheet->setCellValue('B26', $activeUsersCount);
        $sheet->setCellValue('B27', round($invitationCount/$uniqueInvitationCount));
        $sheet->setCellValue('B34', $scanCount);
        $sheet->setCellValue('B35', $acceptedCount);
        $sheet->setCellValue('B36', $rejectedCount);
        $sheet->setCellValue('B37', $this->percentage($uniqueScannedUsersCount, $uniqueUsersCount));
        $sheet->setCellValue('B38', round($scanCount/$uniqueScannedUsersCount));
    }

    private function lastSheet($sheet)
    {
        $settings = app(GeneralSettings::class);
        $startTime = $settings->start_date;
        $endTime = $settings->end_date;
        $nextMonday = $startTime->copy()->startOfWeek(Carbon::MONDAY);
        $allMondays = [];
        $rowIndexs = [];
        $row = 2;
        while ($nextMonday->lte($endTime)) {
            $monday = $nextMonday->format('d.m');
            $sheet->setCellValue([$row, 3], $monday);
            $sheet->setCellValue([$row, 25], $monday);
            $sheet->setCellValue([$row, 44], $monday);
            $monday = $nextMonday->format('d.m.Y');
            $allMondays[$monday] = 0;
            $rowIndexs[$monday] = $row;
            $nextMonday->addWeek();
            $row++;
        }

        $notVerifiedUserCounts = collect($allMondays)
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
            ->toArray();
        foreach($notVerifiedUserCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 4], $value);
        }

        $verifiedUserCounts = collect($allMondays)
            ->merge(User::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as count')
                    ->whereNotNull('phone_number_verified_at')
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => $item->count
                        ];
                    })
            )
            ->toArray();
        foreach($verifiedUserCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 5], $value);
        }

        $firstLevelUserCounts = collect($allMondays)
            ->merge(User::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as count')
                    ->whereNotNull('phone_number_verified_at')
                    ->where('level', 2)
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => $item->count
                        ];
                    })
            )
            ->toArray();
        foreach($firstLevelUserCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 7], $value);
        }

        $otherLevelUserCounts = collect($allMondays)
            ->merge(User::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as count')
                    ->whereNotNull('phone_number_verified_at')
                    ->where('level', '>', 2)
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => $item->count
                        ];
                    })
            )
            ->toArray();
        foreach($otherLevelUserCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 8], $value);
        }

        $ghostUserCounts = collect($allMondays)
            ->merge(User::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as count')
                    ->whereNotNull('phone_number_verified_at')
                    ->where('level', 1)
                    ->where('life', 3)
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => $item->count
                        ];
                    })
            )
            ->toArray();
        foreach($ghostUserCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 9], $value);
        }

        $playedGamesCounts = collect($allMondays)
            ->merge(Game::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as count')
                    ->where('is_finished', 1)
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => $item->count
                        ];
                    })
            )
            ->toArray();
        foreach($playedGamesCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 26], $value);
        }

        $uniquePlayersGamesCounts = collect($allMondays)
            ->merge(Game::selectRaw('WEEK(created_at, 1) as week, COUNT(DISTINCT user_id) as count')
                    ->where('is_finished', 1)
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => $item->count
                        ];
                    })
            )
            ->toArray();
        foreach($uniquePlayersGamesCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 27], $value);
        }

        $averagePlayersGamesCounts = collect($allMondays)
            ->merge(Game::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as total_games, COUNT(DISTINCT user_id) as total_players')
                    ->where('is_finished', 1)
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => round($item->total_games/$item->total_players)
                        ];
                    })
            )
            ->toArray();
        foreach($averagePlayersGamesCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 28], $value);
        }

        $firstLevelGamesCounts = collect($allMondays)
            ->merge(Game::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as count')
                    ->where('is_finished', 1)
                    ->where('before_level', 1)
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => $item->count
                        ];
                    })
            )
            ->toArray();
        foreach($firstLevelGamesCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 29], $value);
        }

        $firstLevelUniqueGamersGamesCounts = collect($allMondays)
            ->merge(Game::selectRaw('WEEK(created_at, 1) as week, COUNT(DISTINCT user_id) as count')
                    ->where('is_finished', 1)
                    ->where('before_level', 1)
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => $item->count
                        ];
                    })
            )
            ->toArray();
        foreach($firstLevelUniqueGamersGamesCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 30], $value);
        }

        $otherLevelGamesCounts = collect($allMondays)
            ->merge(Game::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as count')
                    ->where('is_finished', 1)
                    ->where('before_level', '>', 1)
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => $item->count
                        ];
                    })
            )
            ->toArray();
        foreach($otherLevelGamesCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 31], $value);
        }

        $otherLevelUniqueGamersGamesCounts = collect($allMondays)
            ->merge(Game::selectRaw('WEEK(created_at, 1) as week, COUNT(DISTINCT user_id) as count')
                    ->where('is_finished', 1)
                    ->where('before_level', '>', 1)
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => $item->count
                        ];
                    })
            )
            ->toArray();
        foreach($otherLevelUniqueGamersGamesCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 32], $value);
        }

        $averageOtherLevelGamesCounts = collect($allMondays)
            ->merge(Game::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as total_games, COUNT(DISTINCT user_id) as total_players')
                    ->where('is_finished', 1)
                    ->where('before_level', '>', 1)
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => round($item->total_games/$item->total_players)
                        ];
                    })
            )
            ->toArray();
        foreach($averageOtherLevelGamesCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 33], $value);
        }

        $receiptsCounts = collect($allMondays)
            ->merge(Receipt::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as count')
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => $item->count
                        ];
                    })
            )
            ->toArray();
        foreach($receiptsCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 45], $value);
        }

        $acceptedReceiptsCounts = collect($allMondays)
            ->merge(Receipt::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as count')
                    ->where('receipt_status_id', ReceiptStatus::ACCEPTED)
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => $item->count
                        ];
                    })
            )
            ->toArray();
        foreach($acceptedReceiptsCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 46], $value);
        }

        $rejectedReceiptsCounts = collect($allMondays)
            ->merge(Receipt::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as count')
                    ->where('receipt_status_id', ReceiptStatus::NOT_FOUND)
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => $item->count
                        ];
                    })
            )
            ->toArray();
        foreach($rejectedReceiptsCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 47], $value);
        }

        $uniqueUsersReceiptsCounts = collect($allMondays)
            ->merge(Receipt::selectRaw('WEEK(created_at, 1) as week, COUNT(DISTINCT user_id) as count')
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => $item->count
                        ];
                    })
            )
            ->toArray();
        foreach($uniqueUsersReceiptsCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 48], $value);
        }

        $averageReceiptsCounts = collect($allMondays)
            ->merge(Receipt::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as total_receipts, COUNT(DISTINCT user_id) as total_users')
                    ->groupBy(DB::raw('WEEK(created_at, 1)'))
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            \Carbon\Carbon::now()->startOfYear()->addWeeks($item->week -1)->format('d.m.Y') => round($item->total_receipts/$item->total_users)
                        ];
                    })
            )
            ->toArray();
        foreach($averageReceiptsCounts as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 49], $value);
        }

        $row = 2;
        while ($startTime->lte($endTime)) {
            $day = $startTime->format('d.m');
            $sheet->setCellValue([$row, 14], $day);
            $day = $startTime->format('d.m.Y');
            $allDays[$day] = 0;
            $rowIndexs[$day] = $row;
            $startTime->addDay();
            $row++;
        }

        $userRegistrationsPerDay = collect($allDays)
            ->merge(User::selectRaw("DATE_FORMAT(created_at, '%d.%m.%Y') as date, COUNT(*) as count")
                ->whereNotNull('phone_number_verified_at')
                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d.%m.%Y')"))
                ->get()
                ->pluck('count', 'date')
            )->toArray();
        foreach($userRegistrationsPerDay as $date => $value) {
            $sheet->setCellValue([$rowIndexs[$date], 15], $value);
        }
    }

    private function percentage($value, $target)
    {
        return round(($value / $target) * 100);
    }
}
