<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

final readonly class Quantity
{
    private function __construct(
        private float $value,
    ) {}

    public static function fromFloat(float $value): self
    {
        $quantity = new self($value);
        $quantity->guard();

        return $quantity;
    }
    public function isEqual(Quantity $otherQuantity): bool
    {
        return $this->value === $otherQuantity->value;
    }

    public static function sum(Quantity $a, Quantity $b): self
    {
        return new self($a->value + $b->value);
    }

    private function guard(): void {}

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
