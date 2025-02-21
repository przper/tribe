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

    public function getItemByName(ItemName $name): ?GroceryListItem
    {
        return $this->items->getItem($name);
    }

    /**
     * @throws ItemNotFoundOnGroceryListException
     */
    public function removeItemByName(ItemName $itemName): void
    {
        $this->items->remove($itemName);
    }

    /**
     * @throws ItemNotFoundOnGroceryListException
     */
    public function pickUp(ItemName $itemName): void
    {
        $this->items->pickUp($itemName);
    }
}
