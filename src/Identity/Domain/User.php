<?php

namespace Przper\Tribe\Identity\Domain;

use Przper\Tribe\Shared\Domain\AggregateRoot;
use Przper\Tribe\Shared\Domain\Name;

class User extends AggregateRoot
{
    private function __construct(
        private UserId $id,
        private Name $name,
        private Email $email,
        private HashedPassword $password,
        private Token $token,
    ) {}

    public static function create(UserId $id, Name $name, Email $email, HashedPassword $password, Token $token): self
    {
        $user = new self($id, $name, $email, $password, $token);

        $user->raise(UserCreated::create($id));

        return $user;
    }

    public static function restore(UserId $id, Name $name, Email $email, HashedPassword $password, Token $token): self
    {
        return new self($id, $name, $email, $password, $token);
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }
}
