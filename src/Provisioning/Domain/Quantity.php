<?php

namespace Przper\Tribe\Provisioning\Domain;

final readonly class Quantity
{
    public function __construct(
        public float $value,
    ) {}
}
