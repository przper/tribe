<?php

namespace Tests\Doubles\InMemoryInfrastructure;

use Przper\Tribe\FoodRecipes\Application\Projection\RecipeIndexProjectionInterface;

/**
 * @phpstan-type Recipe array{
 *      id: string,
 *      name: string,
 *      ingredients: string[],
 *  }
 */
final class InMemoryRecipeIndexProjection implements RecipeIndexProjectionInterface
{
    /**
     * @var Recipe[] $projections
     */
    public array $projections = [];

    public function createRecipe(string $recipeId, string $recipeName): void
    {
        $this->projections[$recipeId] = ['name' => $recipeName];
    }

    public function changeRecipeName(string $recipeId, string $recipeName): void
    {
        if (!array_key_exists($recipeId, $this->projections)) {
            return;
        }

        $this->projections[$recipeId]['name'] = $recipeName;
    }
}