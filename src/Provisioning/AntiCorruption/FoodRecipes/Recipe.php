<?php

namespace Przper\Tribe\Provisioning\AntiCorruption\FoodRecipes;

final readonly class Recipe
{
    public function __construct(
        /** @var RecipeIngredient[] $ingredients*/
        public array $ingredients,
    ) {}
}
