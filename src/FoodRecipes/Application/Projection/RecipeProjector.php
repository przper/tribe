<?php

namespace Przper\Tribe\FoodRecipes\Application\Projection;

use Przper\Tribe\FoodRecipes\Domain\Recipe;

class RecipeProjector
{
    public function __construct(
        private readonly RecipeProjection $projection,
    ) {}

    public function createRecipe(Recipe $recipe): void
    {
        $this->projection->createRecipe($recipe->getId(), $recipe->getName());
    }
}
