<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Game;
use App\Models\Receipt;
use App\Models\ReceiptStatus;
use App\Models\User;
use App\Services\GameService;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Decorations\Divider;
use MoonShine\Decorations\Grid;
use MoonShine\Metrics\ValueMetric;
use MoonShine\Pages\Page;

class Dashboard extends Page
{
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->title ?: 'Dashboard';
    }

    public function components(): array
	{
        $uniqueUsersCount = User::verified()->count();
        $gamesCount = Game::finished()->count();
        $uniqueGamers = Game::finished()->distinct('user_id')->count();
        $gamesFirstLevel = Game::finished()->beforeLevel(1)->distinct('user_id')->count();
        $uniqueGamersNextLevel = User::verified()->nextLevel()->count();
        $scanCount = Receipt::count();
        $acceptedCount = Receipt::where('receipt_status_id', ReceiptStatus::ACCEPTED)->count();
        $rejectedCount = Receipt::where('receipt_status_id', ReceiptStatus::NOT_FOUND)->count();
        $uniqueScannedUsersCount = Receipt::distinct('user_id')->count();
		return [
            ActionButton::make(
                __('ui.buttons.total_report'),
                route('moonshine.report.total', ['resourceUri' => $this->uriKey()])
            ),
            Divider::make(),
            Grid::make([
                ValueMetric::make(__('ui.messages.unique_users'))
                    ->value($uniqueUsersCount)
                    ->columnSpan(3),
                ValueMetric::make(__('ui.messages.games_played'))
                    ->value($gamesCount)
                    ->columnSpan(3),
                ValueMetric::make(__('ui.messages.unique_gamers'))
                    ->value($uniqueGamers)
                    ->progress($uniqueUsersCount)
                    ->columnSpan(3),
                ValueMetric::make(__('ui.messages.average_game_count'))
                    ->value(round($gamesCount/$uniqueGamers))
                    ->columnSpan(3),
                ValueMetric::make(__('ui.messages.average_game_users_count'))
                    ->value(round($gamesCount/$uniqueUsersCount))
                    ->columnSpan(3),
                ValueMetric::make(__('ui.messages.total_returning_players'))
                    ->value(GameService::getTotalReturningPlayers())
                    ->columnSpan(3),
                ValueMetric::make(__('ui.messages.games_first_level'))
                    ->value($gamesFirstLevel)
                    ->progress($gamesCount)
                    ->columnSpan(3),
                ValueMetric::make(__('ui.messages.unique_players_first_level'))
                    ->value(GameService::uniquePlayersFirstLevelCount())
                    ->columnSpan(3),
                ValueMetric::make(__('ui.messages.games_other_level'))
                    ->value($gamesCount - $gamesFirstLevel)
                    ->progress($gamesCount)
                    ->columnSpan(3),
                ValueMetric::make(__('ui.messages.unique_gamers_next_level'))
                    ->value($uniqueGamersNextLevel)
                    ->columnSpan(3),
                ValueMetric::make(__('ui.messages.avg_game_players_next_level'))
                    ->value(round(($gamesCount - $gamesFirstLevel)/$uniqueGamers))
                    ->columnSpan(3),
            ]),
            Divider::make(),
            Grid::make([
                ValueMetric::make(__('ui.messages.scan_count'))
                    ->value($scanCount)
                    ->columnSpan(3),
                ValueMetric::make(__('ui.messages.accepted_count'))
                    ->value($acceptedCount)
                    ->columnSpan(3),
                ValueMetric::make(__('ui.messages.not_accepted_count'))
                    ->value($rejectedCount)
                    ->columnSpan(3),
                ValueMetric::make(__('ui.messages.unique_scanned_users_count'))
                    ->value($uniqueScannedUsersCount)
                    ->progress($uniqueUsersCount)
                    ->columnSpan(3),
                ValueMetric::make(__('ui.messages.avg_scanned_users_count'))
                    ->value(round($scanCount/$uniqueScannedUsersCount))
                    ->columnSpan(3),

            ])
        ];
	}
}
