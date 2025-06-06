<?php

namespace Przper\Tribe\Shared\Infrastructure\Symfony;

use Przper\Tribe\Shared\Application\Command\Async\Command;
use Przper\Tribe\Shared\Application\Command\Async\CommandBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

final class AsyncCommandBus implements CommandBus
{
    public function __construct(
        private MessageBusInterface $commandAsyncBus,
    ) {
    }

    public function dispatch(Command $command): void
    {
        try {
            $this->commandAsyncBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious() ?? $e;
        }
    }
}