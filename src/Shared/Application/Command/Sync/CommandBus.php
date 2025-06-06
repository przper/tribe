<?php

namespace Przper\Tribe\Shared\Application\Command\Sync;

interface CommandBus
{
    public function dispatch(Command $command): void;
}