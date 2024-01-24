<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    /** @var int Максимальное количество коинов за 1 игру */
    public int $game_max_coins;

    /** @var int Количество жизней при загрузке чека */
    public int $receipt_life;

    /** @var int Количество жизней для рефералки */
    public int $referal_life;

    public static function group(): string
    {
        return 'general';
    }
}
