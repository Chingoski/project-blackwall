<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Game extends BaseModel
{
    use HasFactory;

    protected $table = 'game';

    protected $fillable = [
        'name',
        'slug',
        'release_date',
        'thumbnail',
        'rating',
    ];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'game_genre', 'game_id', 'genre_id');
    }

    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class, 'game_platform', 'game_id', 'platform_id');
    }
}
