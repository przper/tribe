<?php

namespace Przper\Tribe\FoodRecipes\Application\Query;

use Przper\Tribe\FoodRecipes\Application\Query\Result\RecipeIndex;

interface GetRecipes
{
    /**
     * @return RecipeIndex[]
     */
    public function execute(): array;
}