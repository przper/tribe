<?php

namespace Przper\Tribe\Shared\AntiCorruption;

interface IntegrationEventInterface
{
    public static function getVersion(): int;

    public static function getEventName(): string;
}