<?php

use App\Models\Prize;
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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class);

            $table->foreignIdFor(Prize::class);

            $table->unsignedInteger('spent_balls')
                ->default(0)
                ->comment('потраченные баллы');

            $table->dateTime('winned_date')
                ->nullable();

            $table->boolean('is_approved')
                ->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
