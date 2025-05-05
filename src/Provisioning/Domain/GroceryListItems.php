<?php

namespace Przper\Tribe\Provisioning\Domain;

use Przper\Tribe\Shared\Domain\Collection;
use Przper\Tribe\Shared\Domain\Name;

/** @extends Collection<GroceryListItem> */
class GroceryListItems extends Collection
{
    private function __construct() {}

    /** @var GroceryListItem[] $items */
    private array $items = [];

    public static function create(): self
    {
        return new self();
    }

    public function add(GroceryListItem $item): void
    {
        foreach ($this->items as $i => $storedItem) {
            if ($storedItem->is($item)) {
                $this->items[$i] = $storedItem->add($item);
                return;
            }
        }

        $this->items[] = $item;
    }

    /**
     * @throws ItemNotFoundOnGroceryListException
     */
    public function remove(Name $itemName): void
    {
        foreach ($this->items as $i => $item) {
            if ($item->getName()->is($itemName)) {
                unset($this->items[$i]);
                return;
            }
        }

        throw new ItemNotFoundOnGroceryListException();
    }

    public function getItem(Name $itemName): ?GroceryListItem
    {
        foreach ($this->items as $item) {
            if ($item->getName()->is($itemName)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @throws ItemNotFoundOnGroceryListException
     */
    public function pickUp(Name $itemName): void
    {
        foreach ($this->items as $item) {
            if ($item->getName()->is($itemName)) {
                $item->pickUp();
                return;
            }
        }

        throw new ItemNotFoundOnGroceryListException();
    }

    protected function getItems(): array
    {
        return $this->items;
    }
}
