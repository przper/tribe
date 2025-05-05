<?php

namespace Przper\Tribe\Provisioning\Infrastructure\FoodRecipes;

final readonly class Recipe
{
    public function __construct(
        public string $name,
        /** @var RecipeIngredient[] $ingredients*/
        public array $ingredients,
    ) {}
}
