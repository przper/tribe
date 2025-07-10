<?php

namespace Przper\Tribe\Identity\AntiCorruption\Integration\Authentication;

use Przper\Tribe\Shared\AntiCorruption\IntegrationEventFactory;
use Przper\Tribe\Shared\AntiCorruption\IntegrationEventInterface;

/** @extends IntegrationEventFactory<UserCreated> */
class UserCreatedEventFactory extends IntegrationEventFactory
{
    /**
     * @inheritDoc
     */
    protected function buildEvent(array $data): IntegrationEventInterface
    {
        return new UserCreated(
            userId: $data['userId'],
            email: $data['email'],
            name: $data['name'] ?? null,
        );
    }

    /**
     * @inheritDoc
     */
    public function getExternalEventName(): string
    {
        return UserCreated::getEventName();
    }
}
