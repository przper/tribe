<?php

namespace Przper\Tribe\FoodRecipes\Application\Projection;

interface RecipeDetailProjectionInterface
{
    /**
     * @param string[] $ingredients
     */
    public function createRecipe(string $recipeId, string $recipeName, array $ingredients): void;

    public function changeRecipeName(string $recipeId, string $recipeName): void;

    /**
     * @param string[] $ingredients
     */
    public function changeRecipeIngredients(string $recipeId, array $ingredients): void;
}