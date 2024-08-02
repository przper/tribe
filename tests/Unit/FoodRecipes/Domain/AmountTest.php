<?php

namespace Tests\Unit\FoodRecipes\Domain;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Amount;
use Przper\Tribe\FoodRecipes\Domain\Quantity;
use Przper\Tribe\FoodRecipes\Domain\Unit;
use Tests\Doubles\MotherObjects\AmountMother;

class AmountTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $quantity = Quantity::fromFloat(1);
        $unit = Unit::fromString('spoon');
        $amount = Amount::create($quantity, $unit);

        $this->assertSame($quantity, $amount->getQuantity());
        $this->assertSame($unit, $amount->getUnit());
    }

    #[Test]
    #[DataProvider('isEqual')]
    public function isEqual_to_another_Amount(Amount $a, Amount $b, bool $expected): void
    {
        $this->assertSame($expected, $a->isEqual($b));
    }

    public static function isEqual(): \Generator
    {
        yield [
            AmountMother::new()->kilogram()->build(),
            AmountMother::new()->kilogram()->build(),
            true,
        ];

        yield [
            AmountMother::new()->kilogram(2)->build(),
            AmountMother::new()->kilogram()->build(),
            false,
        ];

        yield [
            AmountMother::new()->can()->build(),
            AmountMother::new()->kilogram()->build(),
            false,
        ];

        yield [
            AmountMother::new()->can(2)->build(),
            AmountMother::new()->kilogram(3)->build(),
            false,
        ];
    }

    #[Test]
    #[DataProvider('isSame')]
    public function isTheSame_to_another_Amount(Amount $a, Amount $b, bool $expected): void
    {
        $this->assertSame($expected, $a->isTheSame($b));
    }

    public static function isSame(): \Generator
    {
        yield [
            AmountMother::new()->kilogram()->build(),
            AmountMother::new()->kilogram()->build(),
            true,
        ];

        yield [
            AmountMother::new()->kilogram(2)->build(),
            AmountMother::new()->kilogram()->build(),
            true,
        ];

        yield [
            AmountMother::new()->can()->build(),
            AmountMother::new()->kilogram()->build(),
            false,
        ];

        yield [
            AmountMother::new()->can(2)->build(),
            AmountMother::new()->kilogram(3)->build(),
            false,
        ];
    }
}
