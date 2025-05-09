<?php

namespace Przper\Tribe\Identity\Domain;

final readonly class MinimalLengthPasswordSpecification implements PasswordSpecificationInterface
{
    public function __construct(
        private int $minimalPasswordLength,
    ) {
    }

    public function isSatisfiedBy(Password $password): bool
    {
        return strlen($password->value) >= $this->minimalPasswordLength;
    }
}