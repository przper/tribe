<?php

namespace Przper\Tribe\Identity\Domain;

use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Domain\UuidGeneratorInterface;
use Random\RandomException;

final readonly class UserFactory
{
    /**
     * @param iterable<EmailSpecificationInterface> $emailSpecifications
     * @param iterable<PasswordSpecificationInterface> $passwordSpecifications
     */
    public function __construct(
        private UuidGeneratorInterface $idGenerator,
        private PasswordHashingServiceInterface $passwordHashingService,
        private iterable $emailSpecifications,
        private iterable $passwordSpecifications,
    ) {
    }

    /**
     * @throws InvalidEmailException
     * @throws InvalidPasswordException
     * @throws InvalidTokenException
     * @throws RandomException
     */
    public function create(Name $name, Email $email, Password $password): User
    {
        foreach ($this->emailSpecifications as $emailSpecification) {
            if (!$emailSpecification->isSatisfiedBy($email)) {
                throw new InvalidEmailException($emailSpecification::class);
            }
        }

        foreach ($this->passwordSpecifications as $passwordSpecification) {
            if (!$passwordSpecification->isSatisfiedBy($password)) {
                throw new InvalidPasswordException($passwordSpecification::class);
            }
        }

        $userId = UserId::fromUuid($this->idGenerator->generate());
        $hashedPassword = $this->passwordHashingService->hash($password);
        $token = Token::create();

        return User::create($userId, $name, $email, $hashedPassword, $token);
    }
}
