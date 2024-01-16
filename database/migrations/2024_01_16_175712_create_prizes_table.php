<?php

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
        Schema::create('prizes', function (Blueprint $table) {
            $table->id();

            $table->string('name_ru')
                ->nullable();

            $table->string('name_kk')
                ->nullable();

            $table->string('name_uz')
                ->nullable();

            $table->unsignedInteger('bal')
                ->default(0)
                ->comment('Балл');

            $table->unsignedInteger('number')
                ->default(0)
                ->comment('Количество призов');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prizes');
    }
};
