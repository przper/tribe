<?php

namespace Przper\Tribe\Identity\Infrastructure\Symfony;

use Przper\Tribe\Identity\Domain\HashedPassword;
use Przper\Tribe\Identity\Domain\Password;
use Przper\Tribe\Identity\Domain\PasswordHashingServiceInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

#[Autoconfigure(public: true)]
final class PasswordHashingService implements PasswordHashingServiceInterface
{
    private PasswordHasherInterface $passwordHasher;

    public function __construct(
        PasswordHasherFactoryInterface $passwordHasherFactory,
    ) {
        $this->passwordHasher = $passwordHasherFactory->getPasswordHasher('common');
    }

    public function hash(Password $password): HashedPassword
    {
        return HashedPassword::fromString($this->passwordHasher->hash($password->getValue()));
    }

    public function isMatching(Password $password, HashedPassword $hashedPassword): bool
    {
        return $this->passwordHasher->verify($hashedPassword->getValue(), $password->getValue());
    }
}
