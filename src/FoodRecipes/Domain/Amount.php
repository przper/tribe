<?php

namespace Przper\Tribe\FoodRecipes\Domain;

class Amount
{
    private function __construct(
        private Quantity $quantity,
        private Unit $unit,
    ) {}

    public static function create(Quantity $quantity, Unit $unit): self
    {
        $amount = new self($quantity, $unit);
        $amount->guard();

        return $amount;
    }

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

    private function guard(): void {}

    public function __toString(): string
    {
        return "$this->quantity [$this->unit]";
    }
}
