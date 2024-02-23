<?php

namespace App\Services;

use App\Models\Game;
use App\Models\InstantPrize;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Settings\GeneralSettings;
use Illuminate\Support\Str;

class GameService
{

    public function __construct(public Rgl $rgl){}

    public function start(User $user): Game
    {
        $payload = [
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'before_life' => $user->life,
            'before_coins' => $user->coin,
            'before_level' => $user->level,
        ];
        if($user->life > 0) {
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
        return Game::make($payload);
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
                $user->level+= 1;
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
            $game->time = $time;
            $game->save();
            return true;
        }
        return false;
    }

    /**
     * @param Game $game
     *
     * @return bool
     */
    public function prize(Game $game): bool
    {
        if(!$game->finish) {
            $user = $game->user;
            if($instantPrize = $game->instantPrize) {
                if(! is_null($instantPrize->code) && is_null($instantPrize->winning_date)) {
                    $this->rgl->send($user->phone_number, $instantPrize->code);
                    $instantPrize->winning_date = now();
                    $instantPrize->save();
                    return true;
                }
            }
        }
        return false;
    }

    public static function getTotalReturningPlayers()
    {
        return DB::table('games as g1')
        ->select(DB::raw('COUNT(DISTINCT g1.user_id) as returning_players'))
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('games as g2')
                ->whereRaw('g2.user_id = g1.user_id')
                ->whereRaw('DATEDIFF(g1.created_at, g2.created_at) = 1');
        })
        ->first()
        ->returning_players;
    }

    public static function uniquePlayersFirstLevelCount()
    {
        return Game::whereIn('user_id', function ($query) {
            $query->select('id')
                ->from('users')
                ->whereNotNull('phone_number_verified_at')
                ->where('level', 1);
        })
        ->count();
    }

}
