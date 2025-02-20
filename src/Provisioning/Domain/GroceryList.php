<?php

namespace Przper\Tribe\Provisioning\Domain;

use Przper\Tribe\Shared\Domain\AggregateRoot;

final class GroceryList extends AggregateRoot
{
    private function __construct(
        private GroceryListItems $items,
    ) {}

    public static function create(): self
    {
        $list = new self(
            items: GroceryListItems::create(),
        );

        return $list;
    }

    public function add(GroceryListItem $item): void
    {
        $this->items->add($item);
    }

    public function getItemByName(string $name): ?GroceryListItem
    {
        foreach ($this->items as $item) {
            if ($item->getItemName()->isEqualTo($name)) {
                return $item;
            }
        }

        return null;
    }
}
