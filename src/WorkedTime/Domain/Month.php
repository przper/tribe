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

    public static function fromIndex(int $index): self
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

    public function toIndex(): int
    {
        return match ($this) {
            self::January => 1,
            self::February => 2,
            self::March => 3,
            self::April => 4,
            self::May => 5,
            self::June => 6,
            self::July => 7,
            self::August => 8,
            self::September => 9,
            self::October => 10,
            self::November => 11,
            self::December => 12,
        };
    }
}
