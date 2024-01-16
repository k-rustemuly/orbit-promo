<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use MoonShine\MoonShineAuth;

class MoonshineAdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MoonShineAuth::model()->query()->create([
            config('moonshine.auth.fields.username', 'email') => config('moonshine.auth.user.username'),
            config('moonshine.auth.fields.name', 'name') => config('moonshine.auth.user.name'),
            config(
                'moonshine.auth.fields.password',
                'password'
            ) => Hash::make(config('moonshine.auth.user.password')),
        ]);
    }
}
