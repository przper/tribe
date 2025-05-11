<?php

namespace Tests\Integration\Identity\Intrastructure\DBAL;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\Identity\Domain\Email;
use Przper\Tribe\Identity\Domain\HashedPassword;
use Przper\Tribe\Identity\Domain\Token;
use Przper\Tribe\Identity\Domain\User;
use Przper\Tribe\Identity\Domain\UserId;
use Przper\Tribe\Identity\Infrastructure\DBAL\UserRepository;
use Przper\Tribe\Shared\Domain\Name;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private UserRepository $repository;
    private Connection $connection;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->repository = $container->get(UserRepository::class);
        $this->connection = $container->get(Connection::class);

        $this->connection->executeQuery(<<<SQL
            INSERT INTO identity_user
                (id, name, email, password, token)
            VALUES (
                '0c53c94a-d821-11ee-8fbc-0242ac190003',
                'Test User',
                'test@example.com',
                '\$2y\$10\$abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01',
                '0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef'
            )
            ON DUPLICATE KEY UPDATE 
                name = VALUES(name),
                email = VALUES(email),
                password = VALUES(password),
                token = VALUES(token);
        SQL);

        $this->assertInstanceOf(UserRepository::class, $this->repository);
    }

    protected function tearDown(): void
    {
        $this->connection->executeQuery(<<<SQL
            DELETE FROM identity_user
            WHERE `id` IN (
                'test',
                '0c53c94a-d821-11ee-8fbc-0242ac190003'
            ); 
        SQL);
    }

    #[Test]
    public function it_can_get_user_by_id(): void
    {
        $id = UserId::fromString('0c53c94a-d821-11ee-8fbc-0242ac190003');

        $result = $this->repository->get($id);

        $this->assertNotNull($result);
        $this->assertInstanceOf(User::class, $result);
        $this->assertSame('Test User', (string) $result->getName());
        $this->assertSame('test@example.com', (string) $result->getEmail());
    }

    #[Test]
    public function it_returns_null_when_user_not_found(): void
    {
        $id = UserId::fromString('non-existent-id');

        $result = $this->repository->get($id);

        $this->assertNull($result);
    }

    #[Test]
    public function it_can_check_if_email_exists(): void
    {
        $email = Email::fromString('test@example.com');

        $result = $this->repository->emailExists($email);

        $this->assertTrue($result);

        $nonExistentEmail = Email::fromString('nonexistent@example.com');

        $result = $this->repository->emailExists($nonExistentEmail);

        $this->assertFalse($result);
    }

    #[Test]
    public function it_can_persist_new_user(): void
    {
        $id = UserId::fromString('test');

        $this->assertNull($this->repository->get($id));

        $password = HashedPassword::fromString(str_pad('new_password_', 60));
        $token = Token::create();
        $user = User::restore(
            $id,
            Name::fromString('New User'),
            Email::fromString('new@example.com'),
            $password,
            $token,
        );

        $this->repository->persist($user);

        $retrievedUser = $this->repository->get($id);

        $this->assertNotNull($retrievedUser);
        $this->assertInstanceOf(User::class, $retrievedUser);
        $this->assertSame('New User', (string) $retrievedUser->getName());
        $this->assertSame('new@example.com', (string) $retrievedUser->getEmail());
        $this->assertSame($password->getValue(), $retrievedUser->getPassword()->getValue());
        $this->assertSame($token->getValue(), $retrievedUser->getToken()->getValue());
    }

    #[Test]
    public function it_can_persist_existing_user(): void
    {
        $id = UserId::fromString('0c53c94a-d821-11ee-8fbc-0242ac190003');

        $user = $this->repository->get($id);
        $this->assertNotNull($user);

        $password = HashedPassword::fromString(str_pad('updated_password', 60));
        $token = Token::create();
        $updatedUser = User::restore(
            $id,
            Name::fromString('Updated User'),
            Email::fromString('updated@example.com'),
            $password,
            $token,
        );

        $this->repository->persist($updatedUser);

        $retrievedUser = $this->repository->get($id);

        $this->assertNotNull($retrievedUser);
        $this->assertSame('Updated User', (string) $retrievedUser->getName());
        $this->assertSame('updated@example.com', (string) $retrievedUser->getEmail());
        $this->assertSame($password->getValue(), $retrievedUser->getPassword()->getValue());
        $this->assertSame($token->getValue(), $retrievedUser->getToken()->getValue());
    }
}