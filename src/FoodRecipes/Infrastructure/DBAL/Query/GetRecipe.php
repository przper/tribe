<?php

namespace Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Query;

use Przper\Tribe\FoodRecipes\Application\Query\GetRecipe as GetRecipeQuery;
use Przper\Tribe\FoodRecipes\Application\Query\Result\RecipeDetail;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Infrastructure\DBAL\Repository\RecipeRepository;

final class GetRecipe implements GetRecipeQuery
{
    public function __construct(
        private readonly RecipeRepository $recipeRepository,
    ) {}

    public function execute(RecipeId $id): ?RecipeDetail
    {
        $recipe = $this->recipeRepository->get($id);

        return $recipe === null
            ? null
            : new RecipeDetail(
                (string) $recipe->getId(),
                (string) $recipe->getName(),
            );
    }
}
