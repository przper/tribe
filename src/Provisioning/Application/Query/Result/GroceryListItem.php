<?php

namespace Przper\Tribe\Provisioning\Application\Query\Result;

final readonly class GroceryListItem
{
    public function __construct(
        public string $name,
        public float $quantity,
        public string $unit,
    ) {}
}
