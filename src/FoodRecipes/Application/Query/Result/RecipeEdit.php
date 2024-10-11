<?php

namespace Przper\Tribe\FoodRecipes\Application\Query\Result;

final readonly class RecipeEdit
{
    /**
     * @param Ingredient[] $ingredients
     */
    public function __construct(
        public string $id,
        public string $name,
        public array $ingredients,
    ) {
    }
}