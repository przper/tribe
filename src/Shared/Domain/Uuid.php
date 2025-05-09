<?php

namespace Przper\Tribe\Shared\Domain;

final readonly class Uuid implements \Stringable
{
    public function __construct(
        public string $value,
    ) {}

    public function __toString(): string
    {
        return $this->value;
    }
}
