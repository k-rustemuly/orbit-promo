<?php

use App\Models\InstantPrize;
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
        Schema::create('games', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(User::class);
            $table->unsignedInteger('before_life')->default(0);
            $table->unsignedInteger('after_life')->default(0);
            $table->unsignedInteger('before_coins')->default(0);
            $table->unsignedInteger('coins')->default(0);
            $table->unsignedInteger('after_coins')->default(0);
            $table->unsignedInteger('before_level')->default(0);
            $table->unsignedInteger('after_level')->default(0);
            $table->foreignIdFor(InstantPrize::class)->nullable();
            $table->unsignedInteger('score')->default(0);
            $table->unsignedInteger('time')->default(0)->comment('время в игре в секундах');
            $table->boolean('is_finished')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
