<?php

namespace Przper\Tribe\Shared\Infrastructure\Symfony;

use Przper\Tribe\Shared\Application\Command\Sync\Command;
use Przper\Tribe\Shared\Application\Command\Sync\CommandBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

final class SyncCommandBus implements CommandBus
{
    public function __construct(
        private MessageBusInterface $commandSyncBus,
    ) {
    }

    public function dispatch(Command $command): void
    {
        try {
            $this->commandSyncBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious() ?? $e;
        }
    }
}