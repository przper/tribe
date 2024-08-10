<?php

namespace Tests\Doubles\InMemoryInfrastructure;

use Przper\Tribe\Shared\Domain\DomainEvent;
use Przper\Tribe\Shared\Domain\DomainEventDispatcherInterface;

final class InMemoryDomainEventDispatcher implements DomainEventDispatcherInterface
{
    /**
     * @var string[] $dispatchedEvents;
     */
    public array $dispatchedEvents = [];

    public function dispatch(DomainEvent ...$domainEvents): void
    {
        foreach ($domainEvents as $domainEvent) {
            $this->dispatchedEvents[] = $domainEvent->name;
        }
    }
}