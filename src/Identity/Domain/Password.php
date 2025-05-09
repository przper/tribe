<?php

namespace Przper\Tribe\Identity\Domain;

final readonly class Password
{
    private function __construct(
        public string $value,
    ) {}

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
