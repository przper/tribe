<?php

namespace Przper\Tribe\FoodRecipes\Application\Projection;

interface RecipeIndexProjectionInterface
{
    public function createRecipe(string $recipeId, string $recipeName): void;

    public function changeRecipeName(string $recipeId, string $recipeName): void;
}
