<?php

namespace Database\Seeders;

use App\Jobs\CreateGameRelatedDataJob;
use App\Models\Game;
use App\Models\GameGenre;
use App\Services\RawgApiService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apiService = new RawgApiService();
        $games = $apiService->getGames();
        $gameCreateData = [];
        $jobData = [];
        $next = $games['next'];

        while (!is_null($next)) {
            foreach ($games['results'] as $game) {
                $gameCreateData[] =
                    [
                        'name'         => $game['name'],
                        'slug'         => $game['slug'],
                        'release_date' => $game['released'],
                        'thumbnail'    => $game['background_image'],
                        'rating'       => $game['rating'],
                    ];
                $jobData[$game['name']] = [
                    'genres'    => $game['genres'],
                    'platforms' => $game['platforms'],
                ];
            }
            Game::query()->upsert($gameCreateData, ['name'], ['name', 'rating', 'release_date', 'thumbnail']);
            foreach ($gameCreateData as $game) {
                CreateGameRelatedDataJob::dispatch($game['name'], $jobData[$game['name']]);
            }

            $gameCreateData = [];
            $jobData = [];
            $games = $apiService->getNextPage($next);
            $next = $games['next'];
        }
    }
}
