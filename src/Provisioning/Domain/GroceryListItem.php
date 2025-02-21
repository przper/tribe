<?php

namespace Przper\Tribe\Provisioning\Domain;

class GroceryListItem
{
    private function __construct(
        private ItemName $itemName,
        private Quantity $quantity,
        private Unit $unit,
        private GroceryListItemStatus $status,
    ) {}

    public static function create(ItemName $itemName, Quantity $quantity, Unit $unit): self
    {
        return new self($itemName, $quantity, $unit, GroceryListItemStatus::ToBuy);
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

    public function getStatus(): GroceryListItemStatus
    {
        return $this->status;
    }

    public function pickUp(): void
    {
        $this->status = GroceryListItemStatus::PickedUp;
    }

    public function isPickedUp(): bool
    {
        return $this->status === GroceryListItemStatus::PickedUp;
    }

    public function isToBuy(): bool
    {
        return $this->status === GroceryListItemStatus::ToBuy;
    }
}
