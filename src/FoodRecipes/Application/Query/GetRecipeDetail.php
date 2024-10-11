<?php

namespace Przper\Tribe\FoodRecipes\Application\Query;

use Przper\Tribe\FoodRecipes\Application\Query\Result\RecipeDetail;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;

interface GetRecipeDetail
{
    public function execute(RecipeId $id): ?RecipeDetail;
}
