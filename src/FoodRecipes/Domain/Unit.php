<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

final readonly class Unit
{
    private function __construct(
        private string $value,
    ) {}

    public static function fromString(string $value): self
    {
        $unit = new self($value);
        $unit->guard();

        return $unit;
    }

    private function guard(): void {}

    public function __toString(): string
    {
        return $this->value;
    }

    public function isEqual(Unit $unit): bool {
        return false;
    }
}
