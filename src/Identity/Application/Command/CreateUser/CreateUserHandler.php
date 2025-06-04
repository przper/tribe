<?php

namespace Przper\Tribe\Identity\Application\Command\CreateUser;

use Przper\Tribe\Identity\Domain\Email;
use Przper\Tribe\Identity\Domain\Password;
use Przper\Tribe\Identity\Domain\UserFactory;
use Przper\Tribe\Identity\Domain\UserRepositoryInterface;
use Przper\Tribe\Shared\Domain\DomainEventDispatcherInterface;
use Przper\Tribe\Shared\Domain\Name;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
class CreateUserHandler
{
    public function __construct(
        private UserFactory $userFactory,
        private UserRepositoryInterface $userRepository,
        private DomainEventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $user = $this->userFactory->create(
            name: Name::fromString($command->name),
            email: Email::fromString($command->email),
        );

        $this->userRepository->persist($user);
        $this->eventDispatcher->dispatch(...$user->pullEvents());
    }
}
