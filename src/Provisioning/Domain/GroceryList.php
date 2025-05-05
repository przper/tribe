<?php

namespace Przper\Tribe\Provisioning\Domain;

use Przper\Tribe\Shared\Domain\AggregateRoot;
use Przper\Tribe\Shared\Domain\Name;

final class GroceryList extends AggregateRoot
{
    private function __construct(
        private GroceryListId $id,
        private GroceryListItems $items,
    ) {}

    public static function create(GroceryListId $id): self
    {
        $list = new self(
            id: $id,
            items: GroceryListItems::create(),
        );

        return $list;
    }

    public static function restore(GroceryListId $id, GroceryListItems $items): self
    {
        return new self($id, $items);
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

    public function getId(): GroceryListId
    {
        return $this->id;
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
