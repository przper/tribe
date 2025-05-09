<?php

namespace Przper\Tribe\Shared\Domain;

abstract readonly class Id
{
    final private function __construct(
        private Uuid $id,
    ) {}

    public static function fromUuid(Uuid $uuid): static
    {
        return new static($uuid);
    }

    public static function fromString(string $id): static
    {
        return new static(new Uuid($id));
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
