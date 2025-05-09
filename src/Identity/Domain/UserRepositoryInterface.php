<?php

namespace Przper\Tribe\Identity\Domain;

interface UserRepositoryInterface
{
    public function get(UserId $userId): ?User;

    public function emailExists(Email $email): bool;

    public function persist(User $user): void;
}
