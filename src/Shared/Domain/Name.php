<?php

declare(strict_types=1);

namespace Przper\Tribe\Shared\Domain;

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

    public function is(self $otherName): bool
    {
        return strtolower($this->value) === strtolower($otherName->value);
    }

    private function guard(): void {}

    public function __toString(): string
    {
        return $this->value;
    }
}
