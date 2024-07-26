<?php

namespace Tests\Doubles\Repositories;

use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;

class InMemoryRecipeRepository implements RecipeRepositoryInterface
{
    private array $recipes = [];

    public function create(Recipe $recipe): void
    {
        $this->recipes[(string) $recipe->getId()] = $recipe;
    }

    public function get(RecipeId $id): ?Recipe
    {
        return $this->recipes[(string) $id] ?? null;
    }

    public function getByIndex(int $index): ?Recipe
    {
        return array_values($this->recipes)[$index] ?? null;
    }
}
