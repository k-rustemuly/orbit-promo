<?php

namespace Database\Seeders;

use App\Models\Prize;
use Illuminate\Database\Seeder;

class PrizesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Prize::create([
            'name_ru' => 'Колонка',
        ]);
        Prize::create([
            'name_ru' => 'Наушники',
        ]);
        Prize::create([
            'name_ru' => 'Планшет',
        ]);
    }
}
