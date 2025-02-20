<?php

namespace Przper\Tribe\Provisioning\Domain;

final readonly class Unit
{
    public function __construct(
        public string $value,
    ) {}
}
