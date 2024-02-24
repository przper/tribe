<?php

namespace Przper\Tribe\FoodRecipes\Domain;

final readonly class Amount
{
    public function __construct(
        private Unit $unit,
        private Quantity $quantity,
    ) {}
}
