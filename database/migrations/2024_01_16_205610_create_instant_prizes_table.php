<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('instant_prizes', function (Blueprint $table) {
            $table->id();

            $table->string('name_ru')
                ->nullable();

            $table->string('name_kk')
                ->nullable();

            $table->string('name_uz')
                ->nullable();

            $table->string('code')
                ->nullable();

            $table->foreignIdFor(User::class, 'winner_id')
                ->nullable();

            $table->dateTime('winning_date')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_instant_prizes');
    }
};
