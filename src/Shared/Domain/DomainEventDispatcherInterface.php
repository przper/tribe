<?php

namespace Przper\Tribe\Shared\Domain;

interface DomainEventDispatcherInterface
{
    public function dispatch(DomainEvent ...$domainEvents): void;
}