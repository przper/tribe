<?php

namespace Przper\Tribe\Shared\Domain;

readonly class Id
{
    public function __construct(
        public string $id,
    ) {}

    public function __toString()
    {
        return $this->id;
    }
}
