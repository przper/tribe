<?php

namespace Przper\Tribe\Identity\Domain;

use Random\RandomException;

final readonly class Token
{
    /**
     * @throws InvalidTokenException
     */
    private function __construct(
        private string $value,
    ) {
        $this->guard();
    }

    /**
     * @throws RandomException|InvalidTokenException
     */
    public static function create(): self
    {
        return new self(bin2hex(random_bytes(32)));
    }

    /**
     * @throws InvalidTokenException
     */
    public static function restore(string $value): self
    {
        return new self($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @throws InvalidTokenException
     */
    private function guard(): void
    {
        if (strlen($this->value) !== 64) {
            throw new InvalidTokenException("Token must be 64 characters long.");
        }


        if (!preg_match('/^[0-9a-f]{64}$/', $this->value)) {
            throw new InvalidTokenException("Token must contain only hexadecimal characters.");
        }
    }
}
