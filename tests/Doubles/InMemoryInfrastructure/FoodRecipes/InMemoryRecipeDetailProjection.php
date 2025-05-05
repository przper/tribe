<?php

namespace Tests\Doubles\InMemoryInfrastructure\FoodRecipes;

use Przper\Tribe\FoodRecipes\Application\Projection\RecipeDetailProjectionInterface;

/**
 * @phpstan-type Recipe array{
 *      id: string,
 *      name: string,
 *      ingredients: string[],
 *  }
 */
final class InMemoryRecipeDetailProjection implements RecipeDetailProjectionInterface
{
    /**
     * @var Recipe[] $projections
     */
    public array $projections = [];

    public function createRecipe(string $recipeId, string $recipeName, array $ingredients): void
    {
        $this->projections[$recipeId] = [
            'name' => $recipeName,
            'ingredients' => $ingredients,
        ];
    }

    public function changeRecipeName(string $recipeId, string $recipeName): void
    {
        if (!array_key_exists($recipeId, $this->projections)) {
            return;
        }

        $this->projections[$recipeId]['name'] = $recipeName;
    }

    public function changeRecipeIngredients(string $recipeId, array $ingredients): void
    {
        if (!array_key_exists($recipeId, $this->projections)) {
            return;
        }

        $this->projections[$recipeId]['ingredients'] = $ingredients;
    }
}
