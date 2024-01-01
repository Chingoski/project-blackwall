<?php

namespace App\Jobs;

use App\Models\Game;
use App\Models\GameGenre;
use App\Models\GamePlatform;
use Illuminate\Support\Facades\DB;

class CreateGameRelatedDataJob extends BaseJob
{
    protected Game $game;

    public function __construct(protected string $gameName, protected array $relatedData)
    {
        $this->game = Game::query()->where('name', '=', $this->gameName)->first();

        parent::__construct();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->seedGenreData();
        $this->seedPlatformData();
    }


    protected function seedGenreData(): void
    {
        $genreCreateData = [];

        foreach ($this->relatedData['genres'] as $genre) {
            $genreCreateData[] = [
                'game_id'    => $this->game->getKey(),
                'genre_id'   => DB::raw("(select id from genre where genre.name = '{$genre['name']}')"),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (empty($genreCreateData)) {
            return;
        }

         GameGenre::query()->insert($genreCreateData);
    }

    protected function seedPlatformData(): void
    {
        $platformCreateData = [];

        foreach ($this->relatedData['platforms'] as $platform) {
            $platformName = $platform['platform']['name'];
            if (!str_contains($platformName, 'PlayStation 4') && !str_contains($platformName, 'PlayStation 5')) {
                continue;
            }
            $platformCreateData[] = [
                'game_id'     => $this->game->getKey(),
                'platform_id' => DB::raw("(select id from platform where platform.name = '{$platformName}')"),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        if (empty($platformCreateData)) {
            return;
        }

        GamePlatform::query()->insert($platformCreateData);
    }
}
