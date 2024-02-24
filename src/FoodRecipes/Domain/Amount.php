<?php

namespace Przper\Tribe\FoodRecipes\Domain;

class Amount
{
    public function __construct(
        private Unit $unit,
        private Quantity $quantity,
    ) {}

    public function getUnit(): Unit
    {
        return $this->unit;
    }

    public function setUnit(Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getQuantity(): Quantity
    {
        return $this->quantity;
    }

    public function setQuantity(Quantity $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
