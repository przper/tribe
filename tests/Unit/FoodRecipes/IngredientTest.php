<?php

namespace Tests\Unit\FoodRecipes;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Name;

class IngredientTest extends TestCase
{
    public function test_it_has_name(): void
    {
        $ingredient = new Ingredient(new Name("Meat"));

        $this->assertInstanceOf(Name::class, $ingredient->getName());
        $this->assertSame("Meat", $ingredient->getName()->value);
    }

    public function test_isTheSameIngredient(): void
    {
        $ingredient1 = new Ingredient(new Name("Meat"));
        $ingredient2 = new Ingredient(new Name("Cheese"));
        $ingredient3 = new Ingredient(new Name("Meat"));

        $this->assertTrue($ingredient1->isTheSameIngredient($ingredient1));
        $this->assertFalse($ingredient1->isTheSameIngredient($ingredient2));
        $this->assertTrue($ingredient1->isTheSameIngredient($ingredient3));
    }
}
