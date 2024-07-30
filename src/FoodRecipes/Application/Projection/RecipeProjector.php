<?php

namespace Przper\Tribe\FoodRecipes\Application\Projection;

use Przper\Tribe\FoodRecipes\Domain\Ingredient;
use Przper\Tribe\FoodRecipes\Domain\Recipe;

class RecipeProjector
{
    public function __construct(
        private readonly RecipeProjection $projection,
    ) {}

    public function createRecipe(Recipe $recipe): void
    {
        $serializedIngredients = [];
        /** @var Ingredient $ingredient */
        foreach ($recipe->getIngredients() as $ingredient) {
            $serializedIngredients[] = sprintf(
                "%s: %s %s",
                $ingredient->getName(),
                $ingredient->getAmount()->getQuantity(),
                $ingredient->getAmount()->getUnit(),
            );
        }

        $this->projection->createRecipe(
            $recipe->getId(),
            $recipe->getName(),
            $serializedIngredients,
        );
    }
}
