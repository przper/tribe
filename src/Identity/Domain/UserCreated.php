<?php

namespace Przper\Tribe\Identity\Domain;

use Przper\Tribe\Shared\Domain\DomainEvent;

final readonly class UserCreated extends DomainEvent
{
    public const EVENT_VERSION = 1;

    public const EVENT_NAME = 'user_created';

    private function __construct(string $aggregateId)
    {
        parent::__construct($aggregateId, self::EVENT_NAME, self::EVENT_VERSION);
    }

    public static function create(UserId $userId): self
    {
        return new self(
            aggregateId: $userId,
        );
    }
}
