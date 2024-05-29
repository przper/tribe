<?php

namespace Tests\Unit\FoodRecipes\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Name;

class IngredientTest extends TestCase
{
    public function test_it_be_created_when_data_is_valid(): void
    {
        $name = Name::fromString('Meat');

        $ingredient = Ingredient::create($name);

        $this->assertSame($name, $ingredient->getName());
    }

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
