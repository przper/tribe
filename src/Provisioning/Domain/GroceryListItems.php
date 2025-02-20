<?php

namespace Przper\Tribe\Provisioning\Domain;

use Przper\Tribe\Shared\Domain\Collection;

/** @extends Collection<GroceryListItem> */
class GroceryListItems extends Collection
{
    /** @var GroceryListItem[] $items */
    private array $items = [];

    public static function create(): self
    {
        return new self();
    }

    public function add(GroceryListItem $item): void
    {
        $this->items[] = $item;
    }

    protected function getItems(): array
    {
        return $this->items;
    }
}
