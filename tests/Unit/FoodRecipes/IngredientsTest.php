<?php

namespace Tests\Unit\FoodRecipes;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Ingredients;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Name;

class IngredientsTest extends TestCase
{
    public function test_it_returns_ingredients(): void
    {
        $ingredients = new Ingredients();

        $this->assertEquals([], $ingredients->getAll());

        $ingredient = new Ingredient(new Name("New Ingredient"));
        $ingredients->add($ingredient);

        $this->assertEquals([$ingredient], $ingredients->getAll());
    }

    public function test_it_checks_if_contains_Ingredient(): void
    {
        $ingredients = new Ingredients();

        $ingredient = new Ingredient(new Name("New Ingredient"));

        $this->assertFalse($ingredients->contains($ingredient));

        $ingredients->add($ingredient);

        $this->assertTrue($ingredients->contains($ingredient));
    }

    public function test_it_can_add_Ingredients(): void
    {
        $ingredients = new Ingredients();

        $ingredient = new Ingredient(new Name("New Ingredient"));

        $ingredients->add($ingredient);

        $this->assertTrue($ingredients->getAll()[0]->isTheSameIngredient($ingredient));
    }
}
