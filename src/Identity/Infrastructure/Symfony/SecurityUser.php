<?php

namespace Przper\Tribe\Identity\Infrastructure\Symfony;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

class SecurityUser implements JWTUserInterface
{
    public function __construct(
        private string $username,
        private array $roles,
    ) {
    }

    public static function createFromPayload($username, array $payload): self
    {
        return new self($username, $payload['roles']);
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function __toString(): string
    {
        return $this->username;
    }
}