<?php

namespace Tests\Unit\FoodRecipes\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Amount;
use Przper\Tribe\FoodRecipes\Domain\Quantity;
use Przper\Tribe\FoodRecipes\Domain\Unit;
use Tests\Doubles\MotherObjects\IngredientMother;

class IngredientTest extends TestCase
{
    public function test_it_be_created_when_data_is_valid(): void
    {
        $ingredient = IngredientMother::new()->kilogramsOfMeat()->build();

        $this->assertSame('Meat', (string) $ingredient->getName());
        $this->assertSame('1', (string) $ingredient->getAmount()->getQuantity());
        $this->assertSame('kilogram', (string) $ingredient->getAmount()->getUnit());
    }

    public function test_isTheSame(): void
    {
        $ingredient1 = IngredientMother::new()->kilogramsOfMeat()->build();
        $ingredient2 = IngredientMother::new()->cansOfTomatoes()->build();
        $ingredient3 = IngredientMother::new()->kilogramsOfMeat(3.0)->build();

        $this->assertTrue($ingredient1->isTheSame($ingredient1));
        $this->assertFalse($ingredient1->isTheSame($ingredient2));
        $this->assertTrue($ingredient1->isTheSame($ingredient3));
    }

    public function test_addAmount(): void
    {
        $ingredient = IngredientMother::new()->kilogramsOfMeat()->build();
        $extraAmount = Amount::create(Quantity::fromFloat(2.0), Unit::fromString('kilogram'));

        $ingredient->addAmount($extraAmount);

        $this->assertSame('3', (string) $ingredient->getAmount()->getQuantity());
    }

    public function test_setAmount(): void
    {
        $ingredient = IngredientMother::new()->kilogramsOfMeat()->build();
        $newAmount = Amount::create(Quantity::fromFloat(2.0), Unit::fromString('kilogram'));

        $ingredient->setAmount($newAmount);

        $this->assertSame('2', (string) $ingredient->getAmount()->getQuantity());
    }
}
