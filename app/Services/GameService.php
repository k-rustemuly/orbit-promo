<?php

namespace App\Services;

use App\Models\Game;
use App\Models\InstantPrize;
use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Support\Str;

class GameService
{

    public function __construct(public Rgl $rgl){}

    public function start(User $user): ?Game
    {
        if($user->life > 0) {
            $payload = [
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'before_life' => $user->life,
                'before_coins' => $user->coin,
                'before_level' => $user->level,
            ];
            if(!$user->is_won_instant_prize) {
                $gift = InstantPrize::notWon()->gift()->orderBy('draw_date')->first();
                if($gift) {
                    $gift->winner_id = $user->id;
                    $gift->save();
                    $payload['instant_prize_id'] = $gift->id;
                    $user->is_won_instant_prize = true;
                }
            }
            $game = Game::create($payload);
            if($game) {
                $user->life -= 1;
                $user->save();
                return $game;
            }
        }
        return null;
    }

    /**
     * @param Game $game
     * @param int $level Уровень игры
     * @param int $score очки
     * @param int $time время в игре в секундах
     * @param bool $finish Завершен?
     *
     * @return bool
     */
    public function finished(Game $game, int $level = 0, int $score = 0, int $time = 0, bool $finish = false): bool
    {
        if(! $game->finish) {
            $coin = app(GeneralSettings::class)->game_max_coins;
            $user = $game->user;
            $game->after_life = $user->life;
            $game->coins = $coin;
            $game->score = $score;
            if($finish) {
                $user->coin+=$coin;
                $user->level= $level;
                $user->save();

                if($instantPrize = $game->instantPrize) {
                    if(! is_null($instantPrize->code) && is_null($instantPrize->winning_date)) {
                        $this->rgl->send($user->phone_number, $instantPrize->code);
                    }
                    $instantPrize->winning_date = now();
                    $instantPrize->save();
                }
            }
            $game->after_coins = $user->coin;
            $game->after_level = $user->level;
            $game->is_finished = $finish;
            $game->save();
            return true;
        }
        return false;
    }
}
