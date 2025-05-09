<?php

namespace Przper\Tribe\Identity\Domain;

final readonly class Email
{
    private function __construct(
        private string $value,
    ) {}

    public static function fromString(string $value): self
    {
        $email = new self($value);
        $email->guard();

        return $email;
    }

    public function is(self $otherEmail): bool
    {
        return strtolower($this->value) === strtolower($otherEmail->value);
    }

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
