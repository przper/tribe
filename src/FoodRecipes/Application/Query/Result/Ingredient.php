<?php

namespace Przper\Tribe\FoodRecipes\Application\Query\Result;

final readonly class Ingredient
{
    public function __construct(
        public string $name,
        public float $quantity,
        public string $unit,
    ) {
    }
}