<?php

namespace Przper\Tribe\Identity\Infrastructure\DBAL;

use Doctrine\DBAL\Connection;
use Przper\Tribe\Identity\Domain\Email;
use Przper\Tribe\Identity\Domain\HashedPassword;
use Przper\Tribe\Identity\Domain\Token;
use Przper\Tribe\Identity\Domain\User;
use Przper\Tribe\Identity\Domain\UserId;
use Przper\Tribe\Identity\Domain\UserRepositoryInterface;
use Przper\Tribe\Shared\Domain\Name;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function getById(UserId $userId): ?User
    {
        $statement = $this->connection->prepare("SELECT * FROM identity_user WHERE id = ?");
        $statement->bindValue(1, $userId);
        $userData = $statement->executeQuery();

        if (!$userData->rowCount()) {
            return null;
        }

        $userData = $userData->fetchAssociative();

        return User::restore(
            UserId::fromString($userData['id']),
            Name::fromString($userData['name']),
            Email::fromString($userData['email']),
        );
    }

    public function getByEmail(Email $email): ?User
    {
        $statement = $this->connection->prepare("SELECT * FROM identity_user WHERE email = ?");
        $statement->bindValue(1, $email);
        $userData = $statement->executeQuery();

        if (!$userData->rowCount()) {
            return null;
        }

        $userData = $userData->fetchAssociative();

        return User::restore(
            UserId::fromString($userData['id']),
            Name::fromString($userData['name']),
            Email::fromString($userData['email']),
        );
    }

    public function emailExists(Email $email): bool
    {
        $statement = $this->connection->prepare("SELECT COUNT(*) as count FROM identity_user WHERE email = ?");
        $statement->bindValue(1, (string)$email);
        $result = $statement->executeQuery();
        $data = $result->fetchAssociative();

        return $data['count'] > 0;
    }

    public function persist(User $user): void
    {
        $this->connection->beginTransaction();

        try {
            $statement = $this->connection->prepare(<<<SQL
                INSERT INTO identity_user
                    (id, name, email)
                VALUES
                    (?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    name = VALUES(name),
                    email = VALUES(email)
            SQL);

            $statement->bindValue(1, (string)$user->getId());
            $statement->bindValue(2, (string)$user->getName());
            $statement->bindValue(3, (string)$user->getEmail());
            $statement->executeStatement();

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}