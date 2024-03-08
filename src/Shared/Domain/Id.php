<?php

namespace Przper\Tribe\Shared\Domain;

readonly class Id
{
    public function __construct(
        private string $id,
    ) {
        $this->guard();
    }

    public function getId(): string
    {
        return $this->id;
    }

    private function guard(): void
    {

    }

    public function __toString(): string
    {
        return $this->id;
    }
}
