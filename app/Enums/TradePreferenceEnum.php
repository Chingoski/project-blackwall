<?php

namespace App\Enums;

enum TradePreferenceEnum: string
{
    case GameTitlesOnly = 'Game Titles Only';
    case Cash = 'Cash';
    case Any = 'Any';


    public static function getRandomTradePreference(): self
    {
        $cases = self::cases();
        return $cases[array_rand($cases)];
    }
}
