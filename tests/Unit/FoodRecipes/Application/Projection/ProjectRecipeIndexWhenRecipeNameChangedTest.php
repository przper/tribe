<?php

namespace Tests\Unit\FoodRecipes\Application\Projection;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Application\Projection\ProjectRecipeIndexWhenRecipeNameChanged;
use Przper\Tribe\FoodRecipes\Application\Projection\RecipeIndexProjectionInterface;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeNameChanged;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;
use Tests\Doubles\MotherObjects\RecipeMother;

class ProjectRecipeIndexWhenRecipeNameChangedTest extends TestCase
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
            ->method('changeRecipeName');

        $projector = new ProjectRecipeIndexWhenRecipeNameChanged($repository, $projection);
        $projector->handle(RecipeNameChanged::create(new RecipeId('test')));
    }
}