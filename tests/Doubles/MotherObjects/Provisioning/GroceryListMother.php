<?php

namespace Tests\Doubles\MotherObjects\Provisioning;

use Przper\Tribe\Provisioning\Domain\GroceryList;
use Przper\Tribe\Provisioning\Domain\GroceryListId;
use Przper\Tribe\Provisioning\Domain\GroceryListItem;

class GroceryListMother
{
    private GroceryListId $id;
    /** @var GroceryListItem[] */
    private array $items = [];

    private function __construct()
    {
        $this->id = new GroceryListId('f3b8ee06-7377-451c-88c1-fde290a61ac4');
    }

    public static function new(): self
    {
        return new self();
    }

    public function id(string $id): self
    {
        $this->id = new GroceryListId($id);
        return $this;
    }

    public function addItem(GroceryListItem $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    public function build(): GroceryList
    {
        $groceryList = GroceryList::create($this->id);

        foreach ($this->items as $item) {
            $groceryList->add($item);
        }

        return $groceryList;
    }
}
