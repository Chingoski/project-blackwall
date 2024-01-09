<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Trade extends BaseModel
{
    use HasFactory;

    protected $table = 'trade';

    protected $with = [
        'offered_games',
    ];

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

    public function games(): HasManyThrough
    {
        return $this->hasManyThrough(Game::class, OfferedTradeGame::class, 'trade_id', 'id', 'id', 'game_id');
    }

    public function offered_games(): HasMany
    {
        return $this->hasMany(OfferedTradeGame::class, 'trade_id', 'id');
    }

    public function createOfferedGames(array $data): static
    {
        if (!isset($data['games']) || empty($data['games'])) {
            return $this;
        }

        $createData = [];

        foreach ($data['games'] as $game) {
            $createData[] = [
                'game_id'     => $game['game_id'],
                'platform_id' => $game['platform_id'],
                'trade_id'    => $this->getKey(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        OfferedTradeGame::query()->insert($createData);

        return $this;
    }

    public function deleteOfferedGames(): void
    {
        OfferedTradeGame::query()->where('trade_id', '=', $this->getKey())->delete();
    }
}
