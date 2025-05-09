<?php

namespace Przper\Tribe\Identity\Domain;

interface UserRepositoryInterface
{
    public function get(UserId $userId): User;

    public function persist(User $user): void;
}
