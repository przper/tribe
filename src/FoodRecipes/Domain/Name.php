<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

class Name
{
    public function __construct(
        public string $value,
    ) {}
}
