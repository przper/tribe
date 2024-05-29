<?php

namespace Przper\Tribe\WorkedTime\Domain;

use DateTimeImmutable;

final readonly class Time
{
    private function __construct(
        private \DateTimeImmutable $datetime,
    ) {}

    public static function fromString(string $time): self
    {
        $time = DateTimeImmutable::createFromFormat('!H:i', $time);

        if ($time === false) {
            throw new InvalidTimeException();
        }

        return new self($time);
    }

    public function isPast(Time $time): bool
    {
        return $this->datetime->getTimestamp() > $time->datetime->getTimestamp();
    }

    public function __toString(): string
    {
        return $this->datetime->format('H:i');
    }
}
