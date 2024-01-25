<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.game_max_coins', 50);
        $this->migrator->add('general.receipt_life', 3);
        $this->migrator->add('general.referal_life', 2);
        $this->migrator->add('general.start_date', null);
        $this->migrator->add('general.end_date', null);
    }
};
