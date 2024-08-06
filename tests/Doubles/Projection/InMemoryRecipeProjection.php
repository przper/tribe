<?php

namespace Tests\Doubles\Projection;

use Przper\Tribe\FoodRecipes\Application\Projection\RecipeProjection;

/**
 * @phpstan-type Recipe array{
 *      id: string,
 *      name: string,
 *      ingredients: string[],
 *  }
 */
class InMemoryRecipeProjection implements RecipeProjection
{
    /**
     * @var array<string, array<string, string>> $recipeIndexProjection
     */
    private array $recipeIndexProjection = [];

    /**
     * @var array<string, Recipe> $recipeDetailProjection
     */
    private array $recipeDetailProjection = [];

    public function persistRecipe(string $id, string $name, array $ingredients): void
    {
        $this->recipeIndexProjection[$id] = ['id' => $id, 'name' => $name];
        $this->recipeDetailProjection[$id] = ['id' => $id, 'name' => $name, 'ingredients' => $ingredients];
    }

    /**
     * @return string[]|null
     */
    public function getIndexProjection(string $id): ?array
    {
        return $this->recipeIndexProjection[$id] ?? null;
    }

    /**
     * @return ?Recipe
     */
    public function getDetailProjection(string $id): ?array
    {
        return $this->recipeDetailProjection[$id] ?? null;
    }
}
