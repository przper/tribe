<?php

namespace Przper\Tribe\Shared\Domain;

readonly class Id
{
    private function __construct(
        private string $id,
    ) {}

    public static function create(string $id): self
    {
        $idClass = new self($id);
        $idClass->guard();

        return $idClass;
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
