<?php

namespace Przper\Tribe\FoodRecipes\Application\Projection;

interface RecipeProjection
{
    /**
     * @param string[] $ingredients
     */
    public function persistRecipe(
        string $id,
        string $name,
        array $ingredients,
    ): void;
}
