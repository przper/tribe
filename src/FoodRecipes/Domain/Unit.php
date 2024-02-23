<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

class Unit
{
    public function __construct(
        public string $value,
    ) {}
}
