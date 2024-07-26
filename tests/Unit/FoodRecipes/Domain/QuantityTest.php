<?php

namespace Tests\Unit\FoodRecipes\Domain;

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
    public function isEqual_to_another_Quantity(): void
    {
        $this->fail('TO DO');
    }
}
