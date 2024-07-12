<?php

namespace Przper\Tribe\Shared\Domain;

abstract readonly class DomainEvent
{
    public function __construct(
        public string $aggregateId,
        public string $name,
        public int $version,
    ) {}
}
