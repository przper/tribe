<?php

namespace Przper\Tribe\Provisioning\Domain;

final readonly class ItemName
{
    public function __construct(
        public string $value,
    ) {}

    public function isEqualTo(ItemName $name): bool
    {
        return $name->value === $this->value;
    }
}
