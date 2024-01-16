<?php

namespace App\LogicValidators\Trade;

use App\Models\GameListing;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CheckIfSameGameAsGameListingIsOfferedLogicValidator
{
    public function __construct(protected GameListing $gameListing, protected array $data)
    {
    }

    public function validate(): void
    {
        if (!isset($this->data['games'])) {
            return;
        }

        foreach ($this->data['games'] as $game) {
            if ($this->gameListing->game_id == $game['game_id']) {
                throw new UnprocessableEntityHttpException('You cannot offer the same game inside a trade.');
            }
        }
    }
}
