<?php

namespace Tests\Unit\FoodRecipes\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Ingredients;
use Tests\Doubles\MotherObjects\FoodRecipes\IngredientMother;

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
        $this->assertCount(1, $ingredients->getAll());

        $ingredients->add($ingredient);

        $updatedAmount = IngredientMother::new()->cansOfTomatoes(2)->build();
        $this->assertTrue($ingredients->getAll()[0]->isTheSame($updatedAmount));
        $this->assertCount(1, $ingredients->getAll());
    }

    public function test_it_can_set_Ingredients(): void
    {
        $ingredients = new Ingredients();

        $ingredientFirstVersion = IngredientMother::new()->cansOfTomatoes()->build();

        $ingredients->set($ingredientFirstVersion);

        $this->assertTrue($ingredients->getAll()[0]->isTheSame($ingredientFirstVersion));
        $this->assertCount(1, $ingredients->getAll());

        $ingredientSecondVersion = IngredientMother::new()->cansOfTomatoes(1.5)->build();
        $ingredients->set($ingredientSecondVersion);

        $this->assertTrue($ingredients->getAll()[0]->isTheSame($ingredientSecondVersion));
        $this->assertCount(1, $ingredients->getAll());
    }

    public function test_it_checks_if_contains_Ingredient(): void
    {
        $ingredients = new Ingredients();

        $ingredient = IngredientMother::new()->cansOfTomatoes()->build();

        $this->assertFalse($ingredients->contains($ingredient));

        $ingredients->add($ingredient);

        $this->assertTrue($ingredients->contains($ingredient));
    }

    #[Test]
    public function it_can_remove_Ingredient(): void
    {
        $ingredients = new Ingredients();

        $ingredient1 = IngredientMother::new()->cansOfTomatoes()->build();
        $ingredient2 = IngredientMother::new()->kilogramsOfMeat()->build();
        $ingredients->add($ingredient1);
        $ingredients->add($ingredient2);

        $this->assertTrue($ingredients->contains($ingredient1));
        $this->assertTrue($ingredients->contains($ingredient2));

        $ingredients->remove($ingredient1);

        $this->assertFalse($ingredients->contains($ingredient1));
        $this->assertTrue($ingredients->contains($ingredient2));
    }
}
