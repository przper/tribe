<?php

namespace Przper\Tribe\Identity\Domain;

use Przper\Tribe\Shared\Domain\Name;
use Przper\Tribe\Shared\Domain\UuidGeneratorInterface;

final readonly class UserFactory
{
    /**
     * @param iterable<EmailSpecificationInterface> $emailSpecifications
     */
    public function __construct(
        private UuidGeneratorInterface $idGenerator,
        private iterable $emailSpecifications,
    ) {
    }

    /**
     * @throws InvalidEmailException
     */
    public function create(Name $name, Email $email): User
    {
        foreach ($this->emailSpecifications as $emailSpecification) {
            if (!$emailSpecification->isSatisfiedBy($email)) {
                throw new InvalidEmailException($emailSpecification::class);
            }
        }

        $userId = UserId::fromUuid($this->idGenerator->generate());

        return User::create($userId, $name, $email);
    }
}
