<?php

namespace Tests\Integration\FoodRecipes\Application;

use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\FoodRecipes\Application\Command\UpdateRecipe\UpdateRecipeCommand;
use Przper\Tribe\FoodRecipes\Application\Command\UpdateRecipe\UpdateRecipeHandler;
use Przper\Tribe\FoodRecipes\Application\Projection\RecipeProjection;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Doubles\MotherObjects\RecipeMother;
use Tests\Doubles\Projection\InMemoryRecipeProjection;
use Tests\Doubles\Repositories\InMemoryRecipeRepository;

class UpdateRecipeHandlerTest extends KernelTestCase
{
    private UpdateRecipeHandler $handler;
    private InMemoryRecipeRepository $repository;
    private InMemoryRecipeProjection $projection;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->handler = self::getContainer()->get(UpdateRecipeHandler::class);
        $this->repository = self::getContainer()->get(RecipeRepositoryInterface::class);
        $this->projection = self::getContainer()->get(RecipeProjection::class);
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
        $this->assertSame('0.5 [kilogram]', (string) $ingredient1->getAmount());

        $indexProjection = $this->projection->getIndexProjection($updatedRecipe->getId());
        $this->assertNotNull($indexProjection);
        $this->assertIsArray($indexProjection);
        $this->assertArrayHasKey('id', $indexProjection);
        $this->assertArrayHasKey('name', $indexProjection);
        $this->assertSame('New & Updated Name', $indexProjection['name']);

        $detailProjection = $this->projection->getDetailProjection($updatedRecipe->getId());
        $this->assertNotNull($detailProjection);
        $this->assertIsArray($detailProjection);
        $this->assertArrayHasKey('id', $detailProjection);
        $this->assertArrayHasKey('name', $detailProjection);
        $this->assertSame('New & Updated Name', $detailProjection['name']);
        $this->assertArrayHasKey('ingredients', $detailProjection);
        $this->assertCount(1, $detailProjection['ingredients']);
        $this->assertSame('Beans: 0.5 kilogram', $detailProjection['ingredients'][0]);
    }
}
