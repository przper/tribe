<?php

namespace Przper\Tribe\FoodRecipes\Application\Query\Result;

final readonly class RecipeDetail
{
    public function __construct(
        public string $id,
        public string $name,
    ) {}
}
