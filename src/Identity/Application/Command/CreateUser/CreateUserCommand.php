<?php

namespace Przper\Tribe\Identity\Application\Command\CreateUser;

use Przper\Tribe\Shared\Application\Command\Async\Command;

final readonly class CreateUserCommand implements Command
{
    public function __construct(
        public string $name,
        public string $email,
    ) {}
}
