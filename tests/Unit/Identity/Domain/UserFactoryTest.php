<?php

namespace Tests\Unit\Identity\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Identity\Domain\Email;
use Przper\Tribe\Identity\Domain\InvalidEmailException;
use Przper\Tribe\Identity\Domain\User;
use Przper\Tribe\Identity\Domain\UserFactory;
use Przper\Tribe\Identity\Domain\EmailSpecificationInterface;
use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Domain\Uuid;
use Przper\Tribe\Shared\Domain\UuidGeneratorInterface;

class UserFactoryTest extends TestCase
{
    private UuidGeneratorInterface $idGenerator;
    private EmailSpecificationInterface $emailSpecification;
    private UserFactory $userFactory;

    protected function setUp(): void
    {
        $this->idGenerator = $this->createMock(UuidGeneratorInterface::class);
        $this->emailSpecification = $this->createMock(EmailSpecificationInterface::class);

        $this->userFactory = new UserFactory(
            $this->idGenerator,
            [$this->emailSpecification],
        );
    }

    #[Test]
    public function create_should_return_user_when_all_validations_pass(): void
    {
        $uuid = new Uuid('f47ac10b-58cc-4372-a567-0e02b2c3d479');
        $name = Name::fromString('John Doe');
        $email = Email::fromString('john.doe@example.com');

        $this->idGenerator
            ->expects($this->once())
            ->method('generate')
            ->willReturn($uuid);

        $this->emailSpecification
            ->expects($this->once())
            ->method('isSatisfiedBy')
            ->with($email)
            ->willReturn(true);

        $user = $this->userFactory->create($name, $email);

        $this->assertInstanceOf(User::class, $user);
    }

    #[Test]
    public function create_should_throw_exception_when_email_validation_fails(): void
    {
        $name = Name::fromString('John Doe');
        $email = Email::fromString('john.doe@example.com');

        $this->emailSpecification
            ->expects($this->once())
            ->method('isSatisfiedBy')
            ->with($email)
            ->willReturn(false);

        $this->expectException(InvalidEmailException::class);
        $this->userFactory->create($name, $email);
    }

    #[Test]
    public function create_should_throw_exception_for_invalid_emails(): void
    {
        $name = Name::fromString('John Doe');
        $email = Email::fromString('john.doe@example.com');

        $this->emailSpecification
            ->method('isSatisfiedBy')
            ->willReturn(false);

        $this->expectException(InvalidEmailException::class);
        $this->userFactory->create($name, $email);
    }
}