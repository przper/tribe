<?php

namespace Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Query;

use Przper\Tribe\FoodRecipes\Application\Query\GetRecipe as GetRecipeQuery;
use Przper\Tribe\FoodRecipes\Application\Query\Result\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Repository\RecipeRepository;

final class GetRecipe implements GetRecipeQuery
{
    public function __construct(
        private readonly RecipeRepository $recipeRepository,
    ) {}

    public function execute(RecipeId $id): ?Recipe
    {
        $recipe = $this->recipeRepository->get($id);

        return $recipe === null ? null : Recipe::fromDomainModel($recipe);
    }
}
