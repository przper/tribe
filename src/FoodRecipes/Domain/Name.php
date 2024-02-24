<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

final readonly class Name
{
    public function __construct(
        public string $value,
    ) {}
}
