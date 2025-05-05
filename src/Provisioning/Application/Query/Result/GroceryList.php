<?php

namespace Przper\Tribe\Provisioning\Application\Query\Result;

final readonly class GroceryList
{
    public function __construct(
        /** @var GroceryListItem[] $items*/
        public array $items = [],
    ) {}
}
