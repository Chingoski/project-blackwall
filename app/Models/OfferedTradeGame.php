<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferedTradeGame extends BaseModel
{
    use HasFactory;

    protected $table = 'offered_trade_game';

    protected $with = [
        'game',
        'platform',
    ];

    protected $fillable = [
        'game_id',
        'trade_id',
        'platform_id',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class, 'platform_id', 'id');
    }
}
