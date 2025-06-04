<?php

namespace Przper\Tribe\Shared\AntiCorruption;

interface IntegrationEventListenerInterface
{
    public function handle(IntegrationEventInterface $event): void;
}