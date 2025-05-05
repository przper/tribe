<?php

namespace Przper\Tribe\Provisioning\Application\Query\Result;

use Przper\Tribe\Provisioning\Domain\GroceryListItemStatus;

final readonly class GroceryListItem
{
    public function __construct(
        public string $name,
        public float $quantity,
        public string $unit,
        public GroceryListItemStatus $status = GroceryListItemStatus::ToBuy,
    ) {}
}
