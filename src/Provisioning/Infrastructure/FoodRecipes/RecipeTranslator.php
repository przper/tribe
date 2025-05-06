<?php

namespace Przper\Tribe\Provisioning\Infrastructure\FoodRecipes;

use Przper\Tribe\FoodRecipes\Application\Query\GetRecipe;
use Przper\Tribe\FoodRecipes\Application\Query\Result\Ingredient;
use Przper\Tribe\FoodRecipes\Application\Query\Result\Recipe as RecipeQuery;

final readonly class RecipeTranslator
{
    public function __construct(
        private GetRecipe $getRecipeDetail,
    ) {}

    public function translate(string $recipeId): ?Recipe
    {
        $foodRecipe = $this->getRecipeDetail->execute($recipeId);

        if (!$foodRecipe instanceof RecipeQuery) {
            return null;
        }

        $ingredients = array_map(
            fn(Ingredient $i) => new RecipeIngredient($i->name, $i->quantity, $i->unit),
            $foodRecipe->ingredients,
        );

        return new Recipe(
            name: $foodRecipe->name,
            ingredients: $ingredients,
        );
    }
}
