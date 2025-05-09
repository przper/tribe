<?php

namespace Przper\Tribe\Identity\Domain;

final readonly class UniqueEmailSpecification implements EmailSpecificationInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function isSatisfiedBy(Email $email): bool
    {
        return !$this->userRepository->emailExists($email);
    }
}
