<?php

namespace Przper\Tribe\Shared\Domain;

final class DomainEventDispatcher implements DomainEventDispatcherInterface
{
    /**
     * @var DomainEventListenerInterface[] $listeners
     */
    private array $listeners;

    /**
     * @param iterable<DomainEventListenerInterface> $listeners
     */
    public function __construct(
        iterable $listeners
    ) {
        $this->listeners = iterator_to_array($listeners);
    }

    public function dispatch(DomainEvent ...$domainEvents): void
    {
        foreach ($domainEvents as $event) {
            foreach ($this->listeners as $listener) {
                $listener->handle($event);
            }
        }
    }
}
