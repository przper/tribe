<?php

namespace Przper\Tribe\Identity\Application\Command\CreateUser;

final readonly class CreateUserCommand
{
    public function __construct(
        public string $name,
        public string $email,
    ) {}
}
