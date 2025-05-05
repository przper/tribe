<?php

namespace Przper\Tribe\Provisioning\Domain;

use Przper\Tribe\Shared\Domain\Amount;
use Przper\Tribe\Shared\Domain\Name;

class GroceryListItem
{
    private function __construct(
        private Name $name,
        private Amount $amount,
        private GroceryListItemStatus $status,
    ) {}

    public static function create(Name $name, Amount $amount): self
    {
        return new self($name, $amount, GroceryListItemStatus::ToBuy);
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
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
