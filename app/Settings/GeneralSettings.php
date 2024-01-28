<?php

namespace App\Settings;

use Carbon\Carbon;
use DateTime;
use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    /** @var int Максимальное количество коинов за 1 игру */
    public int $game_max_coins;

    /** @var int Количество жизней при загрузке чека */
    public int $receipt_life;

    /** @var int Количество жизней по умолчанию */
    public int $default_life;

    /** @var int Количество жизней для рефералки */
    public int $referal_life;

    /** @var \Carbon|null Дата начало акции */
    public ?Carbon $start_date;

    /** @var \Carbon|null Дата окончание акции */
    public ?Carbon $end_date;

    public static function group(): string
    {
        return 'general';
    }
}
