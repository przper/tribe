<?php

namespace Przper\Tribe\FoodRecipes\Domain;

use Przper\Tribe\FoodRecipes\Domain\Recipe;

interface RecipeRepositoryInterface
{
    public function persist(Recipe $recipe): void;

    public function get(RecipeId $id): ?Recipe;
}
