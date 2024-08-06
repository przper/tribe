<?php

namespace Przper\Tribe\FoodRecipes\Domain;

interface RecipeRepositoryInterface
{
    public function persist(Recipe $recipe): void;

    public function get(RecipeId $id): ?Recipe;
}
