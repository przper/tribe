<?php

namespace Przper\Tribe\FoodRecipes\Application\Projection;

interface RecipeProjection
{
    /**
     * @param string[] $ingredients
     */
    public function createRecipe(
        string $id,
        string $name,
        array $ingredients,
    ): void;
}
