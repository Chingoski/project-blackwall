<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GamePlatform;
use App\Models\Genre;
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

        $game = $games['results'][0];
        $gameCreateData = [
            [
                'name'         => $game['name'],
                'slug'         => $game['slug'],
                'release_date' => $game['released'],
                'thumbnail'    => $game['background_image'],
                'rating'       => $game['rating'],
            ],
        ];

        $tagData = [];
        $genreData = [];
        $platformCreateData = [];

        foreach ($game['platforms'] as $platform) {
            $platformName = $platform['platform']['name'];

            if (!str_contains($platformName, 'PlayStation 4') && !str_contains($platformName, 'PlayStation 5')){
                continue;
            }

            $platformCreateData[] = [
                'game_id'     => DB::raw("(select id from game where game.name = '{$game['name']}')"),
                'platform_id' => DB::raw("(select id from platform where platform.name = '{$platformName}')"),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        Game::query()->upsert($gameCreateData, ['name'], ['name', 'rating', 'release_date', 'thumbnail']);
        GamePlatform::query()->insert($platformCreateData);
    }


}
