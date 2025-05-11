<?php

namespace Tests\Doubles\InMemoryInfrastructure\Identity;

use Przper\Tribe\Identity\Domain\Email;
use Przper\Tribe\Identity\Domain\User;
use Przper\Tribe\Identity\Domain\UserId;
use Przper\Tribe\Identity\Domain\UserRepositoryInterface;

class InMemoryUserRepository implements UserRepositoryInterface
{
    /** @var array<string, User> $users */
    public array $users = [];

    public function get(UserId $userId): ?User
    {
        return $this->users[(string) $userId] ?? null;
    }

    public function emailExists(Email $email): bool
    {
        foreach ($this->users as $user) {
            if ($user->getEmail()->is($email)) {
                return true;
            }
        }

        return false;
    }

    public function persist(User $user): void
    {
        $this->users[(string) $user->getId()] = $user;
    }
}