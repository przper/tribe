<?php

namespace Przper\Tribe\FoodRecipes\Application\Query\Result;

final readonly class RecipeIndex
{
    public function __construct(
        public string $id,
        public string $recipe_id,
        public string $name,
    ) {}
}
