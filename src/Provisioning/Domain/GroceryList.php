<?php

namespace Przper\Tribe\Provisioning\Domain;

use Przper\Tribe\Shared\Domain\AggregateRoot;
use Przper\Tribe\Shared\Domain\Name;

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

    public function getItemByName(Name $name): ?GroceryListItem
    {
        return $this->items->getItem($name);
    }

    public function getItems(): GroceryListItems
    {
        return $this->items;
    }

    /**
     * @throws ItemNotFoundOnGroceryListException
     */
    public function removeItemByName(Name $itemName): void
    {
        $this->items->remove($itemName);
    }

    /**
     * @throws ItemNotFoundOnGroceryListException
     */
    public function pickUp(Name $itemName): void
    {
        $this->items->pickUp($itemName);
    }
}
