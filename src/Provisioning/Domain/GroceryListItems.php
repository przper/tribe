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

    /**
     * @throws ItemNotFoundOnGroceryListException
     */
    public function remove(ItemName $itemName): void
    {
        foreach ($this->items as $i => $item) {
            if ($item->getItemName()->isEqualTo($itemName)) {
                unset($this->items[$i]);
                return;
            }
        }

        throw new ItemNotFoundOnGroceryListException();
    }

    public function getItem(ItemName $itemName): ?GroceryListItem
    {
        foreach ($this->items as $item) {
            if ($item->getItemName()->isEqualTo($itemName)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @throws ItemNotFoundOnGroceryListException
     */
    public function pickUp(ItemName $itemName): void
    {
        foreach ($this->items as $item) {
            if ($item->getItemName()->isEqualTo($itemName)) {
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
