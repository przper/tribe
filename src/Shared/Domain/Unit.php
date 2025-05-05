<?php

declare(strict_types=1);

namespace Przper\Tribe\Shared\Domain;

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

    public function is(Unit $unit): bool
    {
        return strtolower($this->value) === strtolower($unit->value);
    }
}
