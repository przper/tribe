<?php

namespace Tests\Unit\FoodRecipes\Application\Projection;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Application\Projection\ProjectRecipeIndexWhenRecipeCreated;
use Przper\Tribe\FoodRecipes\Application\Projection\RecipeIndexProjectionInterface;
use Przper\Tribe\FoodRecipes\Domain\RecipeCreated;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Tests\Doubles\MotherObjects\FoodRecipes\RecipeIdMother;
use Tests\Doubles\MotherObjects\FoodRecipes\RecipeMother;

class ProjectRecipeIndexWhenRecipeCreatedTest extends TestCase
{
    #[Test]
    public function it_calls_RecipeIndex_projection(): void
    {
        $repository = $this->createMock(RecipeRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('get')
            ->willReturn(RecipeMother::new()->build());

        $projection = $this->createMock(RecipeIndexProjectionInterface::class);
        $projection
            ->expects($this->once())
            ->method('createRecipe');

        $projector = new ProjectRecipeIndexWhenRecipeCreated($repository, $projection);
        $projector->handle(RecipeCreated::create(RecipeIdMother::random()));
    }
}
