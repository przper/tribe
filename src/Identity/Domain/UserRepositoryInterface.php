<?php

namespace Przper\Tribe\Identity\Domain;

interface UserRepositoryInterface
{
    public function getById(UserId $userId): ?User;

    public function getByEmail(Email $email): ?User;

    public function emailExists(Email $email): bool;

    public function persist(User $user): void;
}
