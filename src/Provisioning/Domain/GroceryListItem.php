<?php

namespace Przper\Tribe\Provisioning\Domain;

class GroceryListItem
{
    private function __construct(
        private ItemName $itemName,
        private Quantity $quantity,
        private Unit $unit,
    ) {}

    public static function create(ItemName $itemName, Quantity $quantity, Unit $unit): self
    {
        return new self($itemName, $quantity, $unit);
    }

    public function getItemName(): ItemName
    {
        return $this->itemName;
    }

    public function getQuantity(): Quantity
    {
        return $this->quantity;
    }

    public function getUnit(): Unit
    {
        return $this->unit;
    }
}
