<?php

namespace Przper\Tribe\FoodRecipes\Application\Projection;

interface RecipeProjection
{
    public function createRecipe(
        string $id,
        string $name,
        array $ingredients,
    ): void;
}
