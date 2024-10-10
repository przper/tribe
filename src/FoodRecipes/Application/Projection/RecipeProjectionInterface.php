<?php

namespace Przper\Tribe\FoodRecipes\Application\Projection;

interface RecipeProjectionInterface
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
