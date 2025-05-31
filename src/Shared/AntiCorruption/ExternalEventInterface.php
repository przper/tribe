<?php

namespace Przper\Tribe\Shared\AntiCorruption;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('tribe.external_event')]
interface ExternalEventInterface
{
    public function getVersion(): int;

    public function getEventName(): string;
}