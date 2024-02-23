<?php

namespace Tests\Unit\FoodRecipes;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Ingredients;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Name;

class IngredientsTest extends TestCase
{
    public function test_it_can_add_Ingredients()
    {
        $ingredients = new Ingredients();

        $ingredient = new Ingredient();
        $ingredient->name = new Name("New Ingredient");

        $ingredients->add($ingredient);

        $this->assertSame($ingredients->ingredients[0]->name->value, "New Ingredient");
    }
}
