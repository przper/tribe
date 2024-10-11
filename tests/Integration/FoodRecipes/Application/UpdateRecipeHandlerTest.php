<?php

namespace Tests\Integration\FoodRecipes\Application;

use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\FoodRecipes\Application\Command\UpdateRecipe\UpdateRecipeCommand;
use Przper\Tribe\FoodRecipes\Application\Command\UpdateRecipe\UpdateRecipeHandler;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Przper\Tribe\Shared\Domain\DomainEventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Doubles\InMemoryInfrastructure\InMemoryDomainEventDispatcher;
use Tests\Doubles\InMemoryInfrastructure\InMemoryRecipeRepository;
use Tests\Doubles\MotherObjects\RecipeMother;

class UpdateRecipeHandlerTest extends KernelTestCase
{
    private UpdateRecipeHandler $handler;
    private InMemoryRecipeRepository $repository;
    private InMemoryDomainEventDispatcher $eventDispatcher;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->handler = self::getContainer()->get(UpdateRecipeHandler::class);
        $this->repository = self::getContainer()->get(RecipeRepositoryInterface::class);
        $this->eventDispatcher = self::getContainer()->get(DomainEventDispatcherInterface::class);
    }

    #[Test]
    public function it_updates_recipe(): void
    {
        $recipe = RecipeMother::new()->build();

        $this->repository->persist($recipe);

        $command = new UpdateRecipeCommand(
            id: (string) $recipe->getId(),
            name: 'New & Updated Name',
            ingredients: [
                [
                    'name' => 'Beans',
                    'quantity' => 0.5,
                    'unit' => 'kilogram',
                ],
            ],
        );

        call_user_func($this->handler, $command);

        $updatedRecipe = $this->repository->get($recipe->getId());

        $this->assertNotNull($updatedRecipe);
        $this->assertSame('New & Updated Name', (string) $updatedRecipe->getName());
        $this->assertCount(1, $updatedRecipe->getIngredients());

        [$ingredient1] = $updatedRecipe->getIngredients()->getAll();
        $this->assertSame('Beans', (string) $ingredient1->getName());
        $this->assertSame('0.5[kilogram]', (string) $ingredient1->getAmount());

        $this->assertCount(0, $recipe->pullEvents());
        $this->assertCount(2, $this->eventDispatcher->dispatchedEvents);
        $this->assertContains('recipe_name_changed', $this->eventDispatcher->dispatchedEvents);
        $this->assertContains('recipe_ingredients_changed', $this->eventDispatcher->dispatchedEvents);
    }
}
