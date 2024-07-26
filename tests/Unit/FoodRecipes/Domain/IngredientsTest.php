<?php

namespace Tests\Unit\FoodRecipes\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Ingredients;
use Tests\Doubles\MotherObjects\IngredientMother;

class IngredientsTest extends TestCase
{
    public function test_it_returns_ingredients(): void
    {
        $ingredients = new Ingredients();

        $this->assertEquals([], $ingredients->getAll());

        $ingredient = IngredientMother::new()->cansOfTomatoes()->build();
        $ingredients->add($ingredient);

        $this->assertEquals([$ingredient], $ingredients->getAll());
    }

    public function test_it_can_add_Ingredients(): void
    {
        $ingredients = new Ingredients();

        $ingredient = IngredientMother::new()->cansOfTomatoes()->build();

        $ingredients->add($ingredient);

        $this->assertTrue($ingredients->getAll()[0]->isTheSame($ingredient));
    }

    public function test_it_checks_if_contains_Ingredient(): void
    {
        $ingredients = new Ingredients();

        $ingredient = IngredientMother::new()->cansOfTomatoes()->build();

        $this->assertFalse($ingredients->contains($ingredient));

        $ingredients->add($ingredient);

        $this->assertTrue($ingredients->contains($ingredient));
    }
}
