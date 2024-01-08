<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Trade extends BaseModel
{
    use HasFactory;

    protected $table = 'trade';

    protected $fillable = [
        'game_listing_id',
        'trader_user_id',
        'offered_amount',
        'status',
        'owner_confirmed',
        'trader_confirmed',
    ];

    public function trader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trader_user_id', 'id');
    }

    public function game_listing(): BelongsTo
    {
        return $this->belongsTo(GameListing::class, 'game_listing_id', 'id');
    }

    public function offered_games(): HasManyThrough
    {
        return $this->hasManyThrough(Game::class, OfferedTradeGame::class, 'trade_id', 'id', 'id', 'game_id');
    }
}
