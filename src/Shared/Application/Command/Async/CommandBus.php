<?php

namespace Przper\Tribe\Shared\Application\Command\Async;

interface CommandBus
{
    public function dispatch(Command $command): void;
}