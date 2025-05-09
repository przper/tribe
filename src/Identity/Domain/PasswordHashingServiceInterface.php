<?php

namespace Przper\Tribe\Identity\Domain;

interface PasswordHashingServiceInterface
{
    public function hash(Password $password): HashedPassword;

    public function isMatching(Password $password, HashedPassword $hashedPassword): bool;
}
