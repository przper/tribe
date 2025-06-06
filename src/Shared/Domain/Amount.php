<?php

namespace Przper\Tribe\Shared\Domain;

final class Amount
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

    public function isCompatible(Amount $otherAmount): bool
    {
        return $this->unit->is($otherAmount->unit);
    }

    public function isEqual(Amount $otherAmount): bool
    {
        return $this->quantity->isEqual($otherAmount->quantity) && $this->unit->is($otherAmount->unit);
    }

    /**
     * @throws NotMatchingAmountUnitException
     */
    public function add(Amount $amount): void
    {
        if (!$this->isCompatible($amount)) {
            throw new NotMatchingAmountUnitException();
        }

        $this->quantity = Quantity::sum($this->quantity, $amount->quantity);
    }

    private function guard(): void {}

    public function __toString(): string
    {
        return "$this->quantity[$this->unit]";
    }
}
