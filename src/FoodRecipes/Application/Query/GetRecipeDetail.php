<?php

namespace Przper\Tribe\FoodRecipes\Application\Query;

use Przper\Tribe\FoodRecipes\Application\Query\Result\RecipeDetail;

interface GetRecipeDetail
{
    public function execute(string $recipeId): ?RecipeDetail;
}
