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
use Przper\Tribe\FoodRecipes\Domain\RecipeIngredientsChanged;
use Przper\Tribe\FoodRecipes\Domain\RecipeNameChanged;
use Przper\Tribe\FoodRecipes\Domain\Unit;
use Tests\Doubles\MotherObjects\IngredientMother;

class RecipeTest extends TestCase
{
    public function test_it_can_be_created_from_valid_data(): void
    {
        $id = new RecipeId('0c53c94a-d821-11ee-8fbc-0242ac190002');
        $name = Name::fromString('Chili con Carne');
        $ingredients = new Ingredients();
        $ingredients->add(IngredientMother::new()->kilogramsOfMeat()->build());

        $recipe = Recipe::create($id, $name, $ingredients);
        $events = $recipe->pullEvents();

        $this->assertSame('0c53c94a-d821-11ee-8fbc-0242ac190002', (string) $recipe->getId());
        $this->assertSame('Chili con Carne', (string) $recipe->getName());
        $this->assertCount(1, $recipe->getIngredients()->getAll());
        $this->assertSame('Meat', (string) $recipe->getIngredients()->getAll()[0]->getName());
        $this->assertCount(1, $events);
        $this->assertInstanceOf(RecipeCreated::class, $events[0]);
        $this->assertEquals($id, $events[0]->aggregateId);
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
        $events = $recipe->pullEvents();

        $this->assertSame('0c53c94a-d821-11ee-8fbc-0242ac190002', (string) $recipe->getId());
        $this->assertSame('Chili con Carne', (string) $recipe->getName());
        $this->assertCount(2, $recipe->getIngredients()->getAll());
        $this->assertSame('Pork', (string) $recipe->getIngredients()->getAll()[0]->getName());
        $this->assertSame('Tomatoes', (string) $recipe->getIngredients()->getAll()[1]->getName());
        $this->assertCount(0, $events);
    }

    public function test_it_can_change_its_name(): void
    {
        $id = new RecipeId('0c53c94a-d821-11ee-8fbc-0242ac190002');
        $recipe = Recipe::restore(
            $id,
            Name::fromString('Chilli con Carne'),
            new Ingredients(),
        );

        $recipe->changeName(Name::fromString('Spicy Chilli con Carne'));
        $events = $recipe->pullEvents();

        $this->assertSame('Spicy Chilli con Carne', (string) $recipe->getName());
        $this->assertCount(1, $events);
        $this->assertInstanceOf(RecipeNameChanged::class, $events[0]);
        $this->assertEquals($id, $events[0]->aggregateId);
    }

    public function test_it_can_add_an_Ingredient(): void
    {
        $id = new RecipeId('0c53c94a-d821-11ee-8fbc-0242ac190002');
        $recipe = Recipe::restore(
            $id,
            Name::fromString('Chilli con Carne'),
            new Ingredients(),
        );

        $this->assertCount(0, $recipe->getIngredients()->getAll());

        $recipe->addIngredient(IngredientMother::new()->cansOfTomatoes()->build());
        $events = $recipe->pullEvents();

        $this->assertCount(1, $recipe->getIngredients()->getAll());
        $this->assertCount(1, $events);
        $this->assertInstanceOf(RecipeIngredientsChanged::class, $events[0]);
        $this->assertEquals($id, $events[0]->aggregateId);
    }

    public function test_it_can_set_an_Ingredient(): void
    {
        $id = new RecipeId('0c53c94a-d821-11ee-8fbc-0242ac190002');
        $recipe = Recipe::restore(
            $id,
            Name::fromString('Chilli con Carne'),
            new Ingredients(),
        );

        $this->assertCount(0, $recipe->getIngredients()->getAll());

        $recipe->setIngredient(IngredientMother::new()->cansOfTomatoes()->build());
        $events = $recipe->pullEvents();

        $this->assertCount(1, $recipe->getIngredients()->getAll());
        $this->assertCount(1, $events);
        $this->assertInstanceOf(RecipeIngredientsChanged::class, $events[0]);
        $this->assertEquals($id, $events[0]->aggregateId);
    }

    #[Test]
    public function it_can_remove_an_Ingredient(): void
    {
        $id = new RecipeId('0c53c94a-d821-11ee-8fbc-0242ac190002');
        $name = Name::fromString('Chili con Carne');
        $ingredient = IngredientMother::new()->kilogramsOfMeat()->build();
        $ingredients = new Ingredients();
        $ingredients->add($ingredient);

        $recipe = Recipe::restore($id, $name, $ingredients);
        $this->assertCount(1, $recipe->getIngredients()->getAll());

        $recipe->removeIngredient($ingredient);

        $this->assertCount(0, $recipe->getIngredients()->getAll());
    }
}
