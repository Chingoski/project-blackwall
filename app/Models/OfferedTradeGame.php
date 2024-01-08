<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfferedTradeGame extends BaseModel
{
    use HasFactory;

    protected $table = 'offered_trade_game';

    protected $fillable = [
        'game_id',
        'trade_id',
    ];
}
