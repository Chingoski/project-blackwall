<?php

namespace App\Models;

use App\Enums\TradeStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GameListing extends BaseModel
{
    use HasFactory;

    protected $table = 'game_listing';

    protected $fillable = [
        'game_id',
        'owner_id',
        'platform_id',
        'description',
        'trade_preference',
    ];

    protected $with = [
        'game',
        'owner',
        'platform',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class, 'platform_id', 'id');
    }

    public function finished_trade(): HasOne
    {
        return $this->hasOne(Trade::class, 'game_listing_id', 'id')
            ->where('trade.status', '=', TradeStatusEnum::Finished->value);
    }
}
