<?php

namespace Tests\Doubles;

use Przper\Tribe\FoodRecipes\Application\Projection\RecipeProjection;

class InMemoryRecipeProjection implements RecipeProjection
{
    private array $recipeIndexProjection = [];

    private array $recipeDetailProjection = [];

    public function createRecipe(string $id, string $name): void
    {
        $this->recipeIndexProjection[$id] = ['id' => $id, 'name' => $name];
        $this->recipeDetailProjection[$id] = ['id' => $id, 'name' => $name];
    }

    public function getIndexProjection(string $id): ?array
    {
        return $this->recipeIndexProjection[$id] ?? null;
    }

    public function getDetailProjection(string $id): ?array
    {
        return $this->recipeDetailProjection[$id] ?? null;
    }
}
