<?php

namespace App\Enums;

use ReflectionClass;

enum TradeStatusEnum: int
{
    case Pending = 0;
    case Accepted = 1;
    case Finished = 2;
    case Canceled = 3;

    public static function getName(int $value): int|string|null
    {
        $class = new ReflectionClass(__CLASS__);
        foreach ($class->getConstants() as $name => $enum) {
            if ($enum->value == $value) {
                return $name;
            }
        }
        return null;
    }
}
