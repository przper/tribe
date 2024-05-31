<?php

namespace Przper\Tribe\WorkedTime\Domain;

enum Month: string
{
    case January = 'January';
    case February = 'February';
    case March = 'March';
    case April = 'April';
    case May = 'May';
    case June = 'June';
    case July = 'July';
    case August = 'August';
    case September = 'September';
    case October = 'October';
    case November = 'November';
    case December = 'December';

    public static function fromInt(int $index): self
    {
        if ($index < 1 || $index > 12) {
            throw new \InvalidArgumentException('Index must be from 1 to 12 range');
        }

        return match ($index) {
            1 => self::January,
            2 => self::February,
            3 => self::March,
            4 => self::April,
            5 => self::May,
            6 => self::June,
            7 => self::July,
            8 => self::August,
            9 => self::September,
            10 => self::October,
            11 => self::November,
            12 => self::December,
        };
    }
}
