<?php

namespace Tests\Unit\FoodRecipes\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Quantity;

class QuantityTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $quantity = Quantity::fromFloat(1.235);

        $this->assertEquals(1.235, (string) $quantity);
    }
}
