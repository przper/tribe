<?php

declare(strict_types=1);

namespace Przper\Tribe\FoodRecipes\Domain;

final readonly class Name
{
    private function __construct(
        private string $value,
    ) {}

    public static function fromString(string $value): self
    {
        $name = new self($value);
        $name->guard();

        return $name;
    }

    public function isEqual(self $otherName): bool
    {
        return $this->value === $otherName->value;
    }

    private function guard(): void {}

    public function __toString(): string
    {
        return $this->value;
    }
}
