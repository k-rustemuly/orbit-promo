<?php

use App\Models\Prize;
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
        Schema::create('prize_drawing_calendars', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Prize::class)
                ->constrained();

            $table->unsignedInteger('number')
                ->comment('Количество')
                ->default(1);

            $table->dateTime('drawing_at')
                ->comment('Дата и время розыгрыша');

            $table->dateTime('started_at')
                ->comment('Дата и время время начало по факту')
                ->nullable();

            $table->boolean('is_finished')
                ->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prize_drawing_calendars');
    }
};
