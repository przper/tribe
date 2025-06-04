<?php

namespace Przper\Tribe\Identity\Ports\Integration;

use Przper\Tribe\Identity\AntiCorruption\Integration\Authentication\UserCreated;
use Przper\Tribe\Identity\Application\Command\CreateUser\CreateUserCommand;
use Przper\Tribe\Identity\Application\Command\CreateUser\CreateUserHandler;
use Przper\Tribe\Shared\AntiCorruption\IntegrationEventInterface;
use Przper\Tribe\Shared\AntiCorruption\IntegrationEventListenerInterface;

final class UserCreatedIntegrationEventListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private CreateUserHandler $createUserHandler,
    ) {
    }

    public function handle(IntegrationEventInterface $event): void
    {
        if (!$event instanceof UserCreated) {
            return;
        }

        ($this->createUserHandler)(new CreateUserCommand(
            name: $event->name ?? $event->email,
            email: $event->email,
            password: '$ecretPassword1234',
        ));
    }
}