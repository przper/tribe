<?php

namespace FoodRecipes\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Amount;
use Przper\Tribe\FoodRecipes\Domain\Quantity;
use Przper\Tribe\FoodRecipes\Domain\Unit;

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
}
