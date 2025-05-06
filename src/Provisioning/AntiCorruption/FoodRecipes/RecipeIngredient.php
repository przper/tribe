<?php

namespace Przper\Tribe\Provisioning\AntiCorruption\FoodRecipes;

final readonly class RecipeIngredient
{
    public function __construct(
        public string $name,
        public float $quantity,
        public string $unit,
    ) {}
}
