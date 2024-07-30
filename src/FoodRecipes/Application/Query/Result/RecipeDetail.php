<?php

namespace Przper\Tribe\FoodRecipes\Application\Query\Result;

final readonly class RecipeDetail
{
    /** @var string[] $ingredients */
    public array $ingredients;

    public function __construct(
        public string $id,
        public string $recipe_id,
        public string $name,
        string $ingredients,
    ) {
        $this->ingredients = json_decode($ingredients);
    }
}
