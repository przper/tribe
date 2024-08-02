<?php

namespace Tests\Unit\FoodRecipes\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\Amount;
use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Ingredients;
use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Quantity;
use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeCreated;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\Unit;

class RecipeTest extends TestCase
{
    public function test_it_can_be_created_from_valid_data(): void
    {
        $id = new RecipeId('0c53c94a-d821-11ee-8fbc-0242ac190002');
        $name = Name::fromString('Chili con Carne');

        $recipe = Recipe::create($id, $name);
        $events = $recipe->pullEvents();

        $this->assertSame('0c53c94a-d821-11ee-8fbc-0242ac190002', (string) $recipe->getId());
        $this->assertSame('Chili con Carne', (string) $recipe->getName());
        $this->assertCount(1, $events);
        $this->assertInstanceOf(RecipeCreated::class, $events[0]);
    }

    public function test_it_can_be_restored_from_valid_data(): void
    {
        $id = new RecipeId('0c53c94a-d821-11ee-8fbc-0242ac190002');
        $name = Name::fromString('Chili con Carne');
        $ingredients = new Ingredients();
        $ingredients->add(Ingredient::create(
            Name::fromString('Pork'),
            Amount::create(Quantity::fromFloat(1.0), Unit::fromString('kilogram')),
        ));
        $ingredients->add(Ingredient::create(
            Name::fromString('Tomatoes'),
            Amount::create(Quantity::fromFloat(3.0), Unit::fromString('can')),
        ));

        $recipe = Recipe::restore($id, $name, $ingredients);

        $this->assertSame('0c53c94a-d821-11ee-8fbc-0242ac190002', (string) $recipe->getId());
        $this->assertSame('Chili con Carne', (string) $recipe->getName());
        $this->assertCount(2, $recipe->getIngredients()->getAll());
        $this->assertSame('Pork', (string) $recipe->getIngredients()->getAll()[0]->getName());
        $this->assertSame('Tomatoes', (string) $recipe->getIngredients()->getAll()[1]->getName());
    }
}
