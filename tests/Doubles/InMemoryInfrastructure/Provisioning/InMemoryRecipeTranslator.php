<?php

namespace Tests\Doubles\InMemoryInfrastructure\Provisioning;

use Przper\Tribe\Provisioning\AntiCorruption\FoodRecipes\Recipe;
use Przper\Tribe\Provisioning\AntiCorruption\FoodRecipes\RecipeTranslatorInterface;

class InMemoryRecipeTranslator implements RecipeTranslatorInterface
{
    /** @var Recipe[] $recipes */
    public array $recipes = [];

    public function translate(string $recipeId): ?Recipe
    {
        return $this->recipes[$recipeId] ?? null;
    }
}
