<?php

namespace Przper\Tribe\Shared\AntiCorruption;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('tribe.integration_event')]
interface IntegrationEventInterface
{
    public function getVersion(): int;

    public function getEventName(): string;
}