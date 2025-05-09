<?php

namespace Tests\Unit\Identity\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Identity\Domain\Email;
use Przper\Tribe\Identity\Domain\UniqueEmailSpecification;
use Przper\Tribe\Identity\Domain\UserRepositoryInterface;

class UniqueEmailSpecificationTest extends TestCase
{
    #[Test]
    public function isSatisfiedByReturnsTrueWhenEmailDoesNotExist(): void
    {
        $email = Email::fromString('test@test.com');
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects($this->once())
            ->method('emailExists')
            ->with($email)
            ->willReturn(false);

        $specification = new UniqueEmailSpecification($userRepository);

        $result = $specification->isSatisfiedBy($email);

        $this->assertTrue($result);
    }

    #[Test]
    public function isSatisfiedByReturnsFalseWhenEmailExists(): void
    {
        $email = Email::fromString('test@test.com');
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects($this->once())
            ->method('emailExists')
            ->with($email)
            ->willReturn(true);

        $specification = new UniqueEmailSpecification($userRepository);

        $result = $specification->isSatisfiedBy($email);

        $this->assertFalse($result);
    }
}