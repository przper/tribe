<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipies\Domain;

class Name
{
    public function __construct(
        public string $value,
    ) {}
}
