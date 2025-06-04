<?php

namespace Przper\Tribe\Identity\AntiCorruption\Integration\Authentication;

use Przper\Tribe\Shared\AntiCorruption\IntegrationEventInterface;

final readonly class UserCreated implements IntegrationEventInterface
{
    public function __construct(
        public string $userId,
        public string $email,
        public ?string $name = null,
    ) {
    }

    public static function getVersion(): int
    {
        return 1;
    }

    public static function getEventName(): string
    {
        return 'authentication.user.created';
    }
}