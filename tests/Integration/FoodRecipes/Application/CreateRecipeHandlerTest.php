<?php

namespace Tests\Integration\FoodRecipes\Application;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe\CreateRecipeCommand;
use Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe\CreateRecipeHandler;
use Przper\Tribe\FoodRecipes\Application\Projection\RecipeProjector;
use Przper\Tribe\Shared\Infrastructure\Ramsey\IdGenerator;
use Tests\Doubles\Projection\InMemoryRecipeProjection;
use Tests\Doubles\Repositories\InMemoryRecipeRepository;

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
        $handler = new CreateRecipeHandler($repository, new RecipeProjector($projection), new IdGenerator());

        $handler($command);

        $recipeSaved = $repository->getByIndex(0);
        $this->assertNotNull($recipeSaved);
        $this->assertSame('Chilli con Carne', (string) $recipeSaved->getName());
        $this->assertCount(2, $recipeSaved->getIngredients()->getAll());

        [$ingredient1, $ingredient2] = $recipeSaved->getIngredients()->getAll();
        $this->assertSame('Pork', (string) $ingredient1->getName());
        $this->assertSame('Tomatoes', (string) $ingredient2->getName());

        $recipeId = (string) $recipeSaved->getId();
        $indexProjection = $projection->getIndexProjection($recipeId);
        $this->assertNotNull($indexProjection);
        $this->assertIsArray($indexProjection);
        $this->assertArrayHasKey('id', $indexProjection);
        $this->assertArrayHasKey('name', $indexProjection);
        $this->assertSame('Chilli con Carne', $indexProjection['name']);

        $detailProjection = $projection->getDetailProjection($recipeId);
        $this->assertNotNull($detailProjection);
        $this->assertIsArray($detailProjection);
        $this->assertArrayHasKey('id', $detailProjection);
        $this->assertArrayHasKey('name', $detailProjection);
        $this->assertSame('Chilli con Carne', $detailProjection['name']);
        $this->assertArrayHasKey('ingredients', $detailProjection);
        $this->assertCount(2, $detailProjection['ingredients']);
        $this->assertSame('Pork: 1 kilogram', $detailProjection['ingredients'][0]);
        $this->assertSame('Tomatoes: 3 can', $detailProjection['ingredients'][1]);
    }
}
