<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('offered_trade_game', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('game_id');
            $table->unsignedInteger('trade_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('game_id')->references('id')->on('game');
            $table->foreign('trade_id')->references('id')->on('trade');

            $table->index('game_id');
            $table->index('trade_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offered_trade_games');
    }
};
