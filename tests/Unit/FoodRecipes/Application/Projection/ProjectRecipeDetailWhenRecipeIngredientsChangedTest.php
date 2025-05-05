<?php

namespace Tests\Unit\FoodRecipes\Application\Projection;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Application\Projection\ProjectRecipeDetailWhenRecipeIngredientsChanged;
use Przper\Tribe\FoodRecipes\Application\Projection\RecipeDetailProjectionInterface;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeIngredientsChanged;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Tests\Doubles\MotherObjects\FoodRecipes\RecipeMother;

class ProjectRecipeDetailWhenRecipeIngredientsChangedTest extends TestCase
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
            ->method('changeRecipeIngredients');

        $projector = new ProjectRecipeDetailWhenRecipeIngredientsChanged($repository, $projection);
        $projector->handle(RecipeIngredientsChanged::create(new RecipeId('test')));
    }
}
