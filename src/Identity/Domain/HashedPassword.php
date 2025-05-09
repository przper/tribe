<?php

namespace Przper\Tribe\Identity\Domain;

final readonly class HashedPassword
{
    /**
     * @throws InvalidHashedPassword
     */
    private function __construct(
        private string $value,
    ) {
        $this->guard();
    }

    /**
     * @throws InvalidHashedPassword
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function is(HashedPassword $hashedPassword): bool
    {
        return $this->value === $hashedPassword->value;
    }

    /**
     * @throws InvalidHashedPassword
     */
    private function guard(): void
    {
        if (strlen($this->value) < 60) {
            throw new InvalidHashedPassword('Hashed password is too short');
        }
    }
}
