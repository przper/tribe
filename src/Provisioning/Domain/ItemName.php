<?php

namespace Przper\Tribe\Provisioning\Domain;

final readonly class ItemName
{
    public function __construct(
        public string $value,
    ) {}

    public function isEqualTo(string|ItemName $name): bool
    {
        if ($name instanceof ItemName) {
            $name = $name->value;
        }

        return $name === $this->value;
    }
}
