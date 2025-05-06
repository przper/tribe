<?php

namespace Przper\Tribe\Provisioning\AntiCorruption\FoodRecipes;

interface RecipeTranslatorInterface
{
    public function translate(string $recipeId): ?Recipe;
}
