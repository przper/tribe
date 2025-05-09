<?php

namespace Tests\Unit\FoodRecipes\Application\Projection;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Application\Projection\ProjectRecipeDetailWhenRecipeCreated;
use Przper\Tribe\FoodRecipes\Application\Projection\RecipeDetailProjectionInterface;
use Przper\Tribe\FoodRecipes\Domain\RecipeCreated;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Tests\Doubles\MotherObjects\FoodRecipes\RecipeIdMother;
use Tests\Doubles\MotherObjects\FoodRecipes\RecipeMother;

class ProjectRecipeDetailWhenRecipeCreatedTest extends TestCase
{
    #[Test]
    public function it_calls_RecipeDetail_projection(): void
    {
        $repository = $this->createMock(RecipeRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('get')
            ->willReturn(RecipeMother::new()->build());

        $projection = $this->createMock(RecipeDetailProjectionInterface::class);
        $projection
            ->expects($this->once())
            ->method('createRecipe');

        $projector = new ProjectRecipeDetailWhenRecipeCreated($repository, $projection);
        $projector->handle(RecipeCreated::create(RecipeIdMother::random()));
    }
}
