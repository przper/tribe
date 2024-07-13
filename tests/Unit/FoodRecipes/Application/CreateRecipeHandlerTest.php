<?php

namespace Tests\Unit\FoodRecipes\Application;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe\CreateRecipeCommand;
use Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe\CreateRecipeHandler;
use Przper\Tribe\FoodRecipes\Application\Projection\RecipeProjector;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;

class CreateRecipeHandlerTest extends TestCase
{
    public function test_it_persists_recipe(): void
    {
        $command = new CreateRecipeCommand('Chili con Carne');

        $recipeRepositoryMock = $this->createMock(RecipeRepositoryInterface::class);
        $recipeRepositoryMock
            ->expects($this->once())
            ->method('create')
        ;
        $recipeProjectorMock = $this->createMock(RecipeProjector::class);

        $handler = new CreateRecipeHandler($recipeRepositoryMock, $recipeProjectorMock);

        $handler($command);
    }
}
