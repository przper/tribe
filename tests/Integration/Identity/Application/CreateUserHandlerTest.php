<?php

namespace Tests\Integration\Identity\Application;

use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\Identity\Application\Command\CreateUser\CreateUserCommand;
use Przper\Tribe\Identity\Application\Command\CreateUser\CreateUserHandler;
use Przper\Tribe\Identity\Domain\Email;
use Przper\Tribe\Identity\Domain\UserRepositoryInterface;
use Przper\Tribe\Shared\Domain\DomainEventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Doubles\InMemoryInfrastructure\Identity\InMemoryUserRepository;
use Tests\Doubles\InMemoryInfrastructure\Shared\InMemoryDomainEventDispatcher;

class CreateUserHandlerTest extends KernelTestCase
{
    private CreateUserHandler $sut;
    private InMemoryUserRepository $userRepository;
    private InMemoryDomainEventDispatcher $eventDispatcher;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->sut = self::getContainer()->get(CreateUserHandler::class);
        $this->userRepository = self::getContainer()->get(UserRepositoryInterface::class);
        $this->eventDispatcher = self::getContainer()->get(DomainEventDispatcherInterface::class);
    }

    #[Test]
    public function it_creates_user(): void
    {
        $command = new CreateUserCommand(
            name: 'John Doe',
            email: 'john.doe@example.com',
            password: 'StrongPassword123!'
        );

        call_user_func($this->sut, $command);

        $this->assertCount(1, $this->userRepository->users);

        $createdUser = reset($this->userRepository->users);
        $this->assertSame('John Doe', (string) $createdUser->getName());
        $this->assertTrue($createdUser->getEmail()->is(Email::fromString('john.doe@example.com')));
        $this->assertNotEmpty($this->eventDispatcher->dispatchedEvents);
        $this->assertContains('user_created', $this->eventDispatcher->dispatchedEvents);
    }
}