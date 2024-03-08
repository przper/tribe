<?php

namespace Przper\Tribe\Shared\Domain;

abstract class AggregateRoot
{
    /** @var DomainEvent[] */
    protected array $events = [];

    /** @return DomainEvent[] */
    final public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    final protected function raise(DomainEvent $event): void
    {
        $this->events[] = $event;
    }
}
