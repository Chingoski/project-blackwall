<?php

use App\Enums\TradeStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trade', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('game_listing_id');
            $table->decimal('offered_amount')->nullable();
            $table->unsignedInteger('trader_user_id');
            $table->enum('status', [TradeStatusEnum::Pending->value, TradeStatusEnum::Accepted->value, TradeStatusEnum::Finished->value, TradeStatusEnum::Canceled->value])->default(TradeStatusEnum::Pending->value);
            $table->boolean('owner_confirmed')->default(false);
            $table->boolean('trader_confirmed')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('game_listing_id')->references('id')->on('game_listing');
            $table->foreign('trader_user_id')->references('id')->on('user');

            $table->index('game_listing_id');
            $table->index('trader_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade');
    }
};
