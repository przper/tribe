<?php

namespace Tests\Unit\FoodRecipes\Domain;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Quantity;

class QuantityTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $quantity = Quantity::fromFloat(1.235);

        $this->assertEquals(1.235, (string) $quantity);
    }

    #[Test]
    #[DataProvider('isEqual')]
    public function isEqual_to_another_Quantity(Quantity $a, Quantity $b, bool $expected): void
    {
        $this->assertSame($expected, $a->isEqual($b));
    }

    public static function isEqual(): \Generator
    {
        yield [
            Quantity::fromFloat(1.235),
            Quantity::fromFloat(1.235),
            true,
        ];

        yield [
            Quantity::fromFloat(1.235),
            Quantity::fromFloat(10),
            false,
        ];
    }
}
