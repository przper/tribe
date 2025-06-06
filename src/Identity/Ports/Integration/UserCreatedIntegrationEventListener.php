<?php

namespace Przper\Tribe\Identity\Ports\Integration;

use Przper\Tribe\Identity\AntiCorruption\Integration\Authentication\UserCreated;
use Przper\Tribe\Identity\Application\Command\CreateUser\CreateUserCommand;
use Przper\Tribe\Shared\AntiCorruption\IntegrationEventInterface;
use Przper\Tribe\Shared\AntiCorruption\IntegrationEventListenerInterface;
use Przper\Tribe\Shared\Application\Command\Async\CommandBus;

final class UserCreatedIntegrationEventListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private CommandBus $commandBus,
    ) {
    }

    public function handle(IntegrationEventInterface $event): void
    {
        if (!$event instanceof UserCreated) {
            return;
        }

        $this->commandBus->dispatch(new CreateUserCommand(
            name: $event->name ?? $event->email,
            email: $event->email,
        ));
    }
}