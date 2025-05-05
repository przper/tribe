<?php

namespace Tests\Doubles\MotherObjects\Provisioning;

use Przper\Tribe\Provisioning\Domain\GroceryListItem;
use Przper\Tribe\Provisioning\Domain\GroceryListItemStatus;
use Przper\Tribe\Shared\Domain\Amount;
use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Domain\Quantity;
use Przper\Tribe\Shared\Domain\Unit;

class GroceryListItemMother
{
    private Name $name;
    private Quantity $quantity;
    private Unit $unit;
    private GroceryListItemStatus $status = GroceryListItemStatus::ToBuy;

    private function __construct()
    {
        $this->name = Name::fromString("Item");
        $this->quantity = Quantity::fromFloat(1.0);
        $this->unit = Unit::fromString("pcs");
    }

    public static function new(): self
    {
        return new self();
    }

    public function build(): GroceryListItem
    {
        return GroceryListItem::restore($this->name, Amount::create($this->quantity, $this->unit), $this->status);
    }

    public function name(string $string): self
    {
        $this->name = Name::fromString($string);
        return $this;
    }

    public function quantity(float $quantity): self
    {
        $this->quantity = Quantity::fromFloat($quantity);
        return $this;
    }

    public function unit(string $unit): self
    {
        $this->unit = Unit::fromString($unit);
        return $this;
    }

    public function toBuy(): self
    {
        $this->status = GroceryListItemStatus::ToBuy;
        return $this;
    }


    public function pickedUp(): self
    {
        $this->status = GroceryListItemStatus::PickedUp;
        return $this;
    }
}
