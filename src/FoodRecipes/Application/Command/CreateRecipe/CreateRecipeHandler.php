<?php

namespace Przper\Tribe\FoodRecipes\Application\Command\CreateRecipe;

use Przper\Tribe\FoodRecipes\Domain\Name;
use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;

final class CreateRecipeHandler
{
    public function __construct(
        private RecipeRepositoryInterface $repository,
    ) {}

    public function __invoke(CreateRecipeCommand $command): void
    {
        $recipe = Recipe::create(
            new RecipeId('test'),
            Name::fromString($command->name),
        );

        $this->repository->persist($recipe);
    }
}
