<?php

namespace Przper\Tribe\FoodRecipes\Application\Query\Result;

final readonly class RecipeDetail
{
    /** @var Ingredient[] */
    public array $ingredients;

    public function __construct(
        public string $id,
        public string $name,
        string $ingredients,
    ) {
        $this->ingredients = array_map(
            fn (array $i) => new Ingredient(...$i),
            json_decode($ingredients, true),
        );
    }
}
