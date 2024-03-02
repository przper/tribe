<?php

namespace Tests\Unit\FoodRecipes;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Name;

class IngredientTest extends TestCase
{
    public function test_isTheSameIngredient(): void
    {
        $ingredient1 = Ingredient::create(Name::fromString("Meat"));
        $ingredient2 = Ingredient::create(Name::fromString("Cheese"));
        $ingredient3 = Ingredient::create(Name::fromString("Meat"));

        $this->assertTrue($ingredient1->equals($ingredient1));
        $this->assertFalse($ingredient1->equals($ingredient2));
        $this->assertTrue($ingredient1->equals($ingredient3));
    }
}
