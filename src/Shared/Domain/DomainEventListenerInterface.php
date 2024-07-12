<?php

namespace Przper\Tribe\Shared\Domain;

interface DomainEventListenerInterface
{
    public function handle(DomainEvent $event): void;
}