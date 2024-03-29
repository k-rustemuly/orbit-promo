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
        Schema::create('receipt_statuses', function (Blueprint $table) {
            $table->id();

            $table->string('name_ru')
                ->nullable();

            $table->string('name_kk')
                ->nullable();

            $table->string('name_uz')
                ->nullable();

            $table->string('color', 15)->default('#000000');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_statuses');
    }
};
