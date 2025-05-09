<?php

namespace Tests\Integration\FoodRecipes\Application;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe\CreateRecipeCommand;
use Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe\CreateRecipeHandler;
use Przper\Tribe\Shared\Infrastructure\Ramsey\UuidGenerator;
use Tests\Doubles\InMemoryInfrastructure\FoodRecipes\InMemoryRecipeRepository;
use Tests\Doubles\InMemoryInfrastructure\Shared\InMemoryDomainEventDispatcher;

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
        $eventDispatcher = new InMemoryDomainEventDispatcher();

        $handler = new CreateRecipeHandler(
            $repository,
            new UuidGenerator(),
            $eventDispatcher,
        );

        $handler($command);

        $recipeSaved = $repository->getByIndex(0);
        $this->assertNotNull($recipeSaved);
        $this->assertSame('Chilli con Carne', (string) $recipeSaved->getName());
        $this->assertCount(2, $recipeSaved->getIngredients());

        [$ingredient1, $ingredient2] = $recipeSaved->getIngredients()->getAll();
        $this->assertSame('Pork', (string) $ingredient1->getName());
        $this->assertSame('1[kilogram]', (string) $ingredient1->getAmount());
        $this->assertSame('Tomatoes', (string) $ingredient2->getName());
        $this->assertSame('3[can]', (string) $ingredient2->getAmount());

        $this->assertCount(1, $eventDispatcher->dispatchedEvents);
        $this->assertContains('recipe_created', $eventDispatcher->dispatchedEvents);
    }
}
