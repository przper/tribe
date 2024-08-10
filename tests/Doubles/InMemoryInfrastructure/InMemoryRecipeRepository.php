<?php

namespace Tests\Doubles\InMemoryInfrastructure;

use Przper\Tribe\FoodRecipes\Domain\Recipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\FoodRecipes\Domain\RecipeRepositoryInterface;

class InMemoryRecipeRepository implements RecipeRepositoryInterface
{
    /**
     * @var Recipe[] $recipes
     */
    private array $recipes = [];

    public function persist(Recipe $recipe): void
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
