<?php

namespace Database\Seeders;

use App\Enums\TradePreferenceEnum;
use App\Models\Game;
use App\Models\GameListing;
use App\Models\User;
use Illuminate\Database\Seeder;

class GameListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->get();

        /** @var User $user */
        foreach ($users as $user) {

            for ($count = 0; $count < 5; $count++) {
                /** @var Game $game */
                $game = Game::query()->inRandomOrder()->first();

                GameListing::query()->create([
                    'owner_id'         => $user->getKey(),
                    'game_id'          => $game->getKey(),
                    'platform_id'      => $game->platforms()->inRandomOrder()->first()->getKey(),
                    'description'      => 'Lorem Impsum',
                    'trade_preference' => TradePreferenceEnum::getRandomTradePreference()->value,
                ]);
            }
        }
    }
}
