<?php

namespace Tests\Unit\Identity\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Identity\Domain\Email;
use Przper\Tribe\Identity\Domain\HashedPassword;
use Przper\Tribe\Identity\Domain\Token;
use Przper\Tribe\Identity\Domain\User;
use Przper\Tribe\Identity\Domain\UserCreated;
use Przper\Tribe\Identity\Domain\UserId;
use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Domain\Uuid;

class UserTest extends TestCase
{
    #[Test]
    public function it_can_be_created_from_valid_data(): void
    {
        $id = UserId::fromUuid(new Uuid('0c53c94a-d821-11ee-8fbc-0242ac190002'));
        $name = Name::fromString('John Doe');
        $email = Email::fromString('john@example.com');
        $password = HashedPassword::fromString(str_pad('hashed_password', 60, '0'));
        $token = Token::create();

        $sut = User::create($id, $name, $email, $password, $token);
        $events = $sut->pullEvents();

        $this->assertSame('0c53c94a-d821-11ee-8fbc-0242ac190002', (string) $sut->getId());
        $this->assertSame('John Doe', (string) $sut->getName());
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserCreated::class, $events[0]);
        $this->assertEquals($id, $events[0]->aggregateId);
    }

    #[Test]
    public function it_can_be_restored_from_valid_data(): void
    {
        $id = UserId::fromUuid(new Uuid('0c53c94a-d821-11ee-8fbc-0242ac190002'));
        $name = Name::fromString('John Doe');
        $email = Email::fromString('john@example.com');
        $password = HashedPassword::fromString(str_pad('hashed_password', 60, '0'));
        $token = Token::create();

        $sut = User::restore($id, $name, $email, $password, $token);
        $events = $sut->pullEvents();

        $this->assertSame('0c53c94a-d821-11ee-8fbc-0242ac190002', (string) $sut->getId());
        $this->assertSame('John Doe', (string) $sut->getName());
        $this->assertCount(0, $events);
    }
}