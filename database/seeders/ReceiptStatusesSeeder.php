<?php

namespace Database\Seeders;

use App\Models\ReceiptStatus;
use Illuminate\Database\Seeder;

class ReceiptStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        ReceiptStatus::create([
            'name_kk' => '',
            'name_ru' => 'Позиция "Орбит" не найдена!',
            'name_uz' => '',
            'color'   => '#ff0000'
        ]);
        ReceiptStatus::create([
            'name_kk' => '',
            'name_ru' => 'Принято',
            'name_uz' => '',
            'color'   => '#008000'
        ]);
        ReceiptStatus::create([
            'name_kk' => '',
            'name_ru' => 'На проверке',
            'name_uz' => '',
            'color'   => '#ff8000'
        ]);
    }
}
