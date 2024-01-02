<?php

namespace App\LogicValidators\GameListing;

use App\Models\Game;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class GameListingPlatformIsCorrectLogicValidator
{
    public function validateCreate(array $data): void
    {
        /** @var Game $game */
        $game = Game::query()
            ->find($data['game_id']);

        $platformExists = $game->platforms()
            ->where('platform.id', '=', $data['platform_id'])
            ->exists();

        if ($platformExists) {
            return;
        }

        throw new UnprocessableEntityHttpException('The selected game platform is invalid');
    }
}
