<?php

use App\Enums\TradePreferenceEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_listing', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('game_id');
            $table->unsignedInteger('owner_id');
            $table->unsignedInteger('platform_id');
            $table->string('description')->nullable();
            $table->enum('trade_preference', [TradePreferenceEnum::GameTitlesOnly->value, TradePreferenceEnum::Cash->value, TradePreferenceEnum::Any->value])->default(TradePreferenceEnum::Any->value);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('game_id')->references('id')->on('game');
            $table->foreign('owner_id')->references('id')->on('user');
            $table->foreign('platform_id')->references('id')->on('platform');

            $table->index('game_id');
            $table->index('owner_id');
            $table->index('platform_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_listing');
    }
};
