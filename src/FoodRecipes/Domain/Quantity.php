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

    private function guard(): void {}

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
