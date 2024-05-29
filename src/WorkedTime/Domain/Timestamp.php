<?php

namespace Przper\Tribe\WorkedTime\Domain;

use DateTimeImmutable;
use DateTimeInterface;

final readonly class Timestamp
{
    private function __construct(
        private \DateTimeImmutable $datetime,
    ) {
    }

    public static function fromDateTimeInterface(DateTimeInterface $datetime): self
    {
        $timestamp = new self(DateTimeImmutable::createFromInterface($datetime));
        $timestamp->guard();

        return $timestamp;
    }

    private function guard(): void {}

    public function __toString(): string
    {
        return $this->datetime->format('Y-m-d H:i');
    }
}