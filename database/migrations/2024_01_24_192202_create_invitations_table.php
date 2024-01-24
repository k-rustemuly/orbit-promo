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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'owner_id')
                ->comment('Пригласил(а)');
            $table->foreignIdFor(User::class, 'friend_id')
                ->comment('Друг');
            $table->unsignedInteger('life')
                ->default(0)
                ->comment('Сколько за это получил жизни');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
