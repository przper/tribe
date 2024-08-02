<?php

namespace Tests\Unit\FoodRecipes\Domain;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Unit;

class UnitTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $unit = Unit::fromString('spoon');

        $this->assertEquals('spoon', $unit);
    }

    #[Test]
    #[DataProvider('isEqual')]
    public function isEqual_to_another_Unit(Unit $a, Unit $b, bool $expected): void
    {
        $this->assertSame($expected, $a->isEqual($b));
    }

    public static function isEqual(): \Generator
    {
        yield [
            Unit::fromString('kilogram'),
            Unit::fromString('kilogram'),
            true,
        ];

        yield [
            Unit::fromString('Kilogram'),
            Unit::fromString('kilogram'),
            true,
        ];

        yield [
            Unit::fromString('Kilogram'),
            Unit::fromString('KILOGRAM'),
            true,
        ];

        yield [
            Unit::fromString('kilogram'),
            Unit::fromString('can'),
            false,
        ];
    }
}
