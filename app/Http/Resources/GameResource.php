<?php

namespace App\Http\Resources;

use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'userId' => $this->id,
            'userCoins' => $this->before_life,
            'userEnergy' => $this->before_coins,
            'levelNumber' => $this->before_level,
            'coinWin' => app(GeneralSettings::class)->game_max_coins,
            'lang' => app()->getLocale(),
            'instantGift' => $this->instant_gift,
        ];
    }
}
