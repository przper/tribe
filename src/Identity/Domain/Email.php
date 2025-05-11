<?php

namespace Przper\Tribe\Identity\Domain;

final readonly class Email
{
    /**
     * @throws InvalidEmailException
     */
    private function __construct(
        private string $value,
    ) {
        $this->guard();
    }

    /**
     * @throws InvalidEmailException
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function is(Email $email): bool
    {
        return $this->value === $email->value;
    }

    /**
     * @throws InvalidEmailException
     */
    private function guard(): void
    {
        if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException($this->value);
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
