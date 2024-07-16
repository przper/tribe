<?php

namespace Tests\Integration\FoodRecipes\Application;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe\CreateRecipeCommand;
use Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe\CreateRecipeHandler;
use Przper\Tribe\FoodRecipes\Application\Projection\RecipeProjector;
use Tests\Doubles\InMemoryRecipeProjection;
use Tests\Doubles\InMemoryRecipeRepository;

class CreateRecipeHandlerTest extends TestCase
{
    public function test_it_creates_recipe(): void
    {
        $command = new CreateRecipeCommand(
            name: 'Chilli con Carne',
            ingredients: [
                [
                    'name' => 'Pork',
                    'quantity' => 1.0,
                    'unit' => 'kilogram',
                ],
                [
                    'name' => 'Tomatoes',
                    'quantity' => 3.0,
                    'unit' => 'can',
                ],
            ],
        );

        $repository = new InMemoryRecipeRepository();
        $projection = new InMemoryRecipeProjection();
        $handler = new CreateRecipeHandler($repository, new RecipeProjector($projection));

        $handler($command);

        $recipeSaved = $repository->getByIndex(0);
        $this->assertNotNull($recipeSaved);
        $this->assertSame('Chilli con Carne', (string) $recipeSaved->getName());
        $this->assertCount(2, $recipeSaved->getIngredients()->getAll());

        [$ingredient1, $ingredient2] = $recipeSaved->getIngredients()->getAll();
        $this->assertSame('Pork', (string) $ingredient1->getName());
        $this->assertSame('Tomatoes', (string) $ingredient2->getName());

        $recipeId = (string) $recipeSaved->getId();
        $this->assertNotNull($projection->getIndexProjection($recipeId));
        $this->assertIsArray($projection->getIndexProjection($recipeId));
        $this->assertArrayHasKey('id', $projection->getIndexProjection($recipeId));
        $this->assertArrayHasKey('name', $projection->getIndexProjection($recipeId));
        $this->assertSame('Chilli con Carne', $projection->getIndexProjection($recipeId)['name']);
        $this->assertNotNull($projection->getDetailProjection($recipeId));
        $this->assertIsArray($projection->getDetailProjection($recipeId));
        $this->assertArrayHasKey('id', $projection->getDetailProjection($recipeId));
        $this->assertArrayHasKey('name', $projection->getDetailProjection($recipeId));
        $this->assertSame('Chilli con Carne', $projection->getDetailProjection($recipeId)['name']);
    }
}
