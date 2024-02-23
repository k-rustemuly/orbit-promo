<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameFinishRequest;
use App\Http\Requests\GamePrizeSendRequest;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Services\GameService;
use Illuminate\Support\Facades\Auth;

class GameController extends BaseController
{
    public function start(GameService $service)
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        if($game = $service->start($user)) {
            return $this->success(new GameResource($game));
        }
        return $this->error(__('ui.messages.life_error'));
    }

    public function finish(GameFinishRequest $request, GameService $service)
    {
        $data = $request->validated();
        $id = $data['userId'];
        $level = (int) $data['level'];
        $score = (int) $data['score'];
        $time = (int) $data['time'];
        $finish = (bool) $data['finish'];
        $game = Game::find($id);
        if($game && $game->user_id == auth()->id()) {
            if($service->finished($game, $level, $score, $time, $finish)) {
                return $this->success();
            }
        }
        return $this->error(__('ui.messages.error_finished_game'));
    }

    public function prize(GamePrizeSendRequest $request, GameService $service)
    {
        $data = $request->validated();
        $game = Game::find($data['userId']);
        if($game && $game->user_id == auth()->id()) {
            if($service->prize($game)) {
                return $this->success();
            }
        }
        return $this->error(__('ui.messages.error_finished_game'));
    }

    public function gamePage()
    {
            return view('game.index');
    }
}
